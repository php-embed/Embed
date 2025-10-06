<?php
declare(strict_types = 1);

namespace Embed\Http;

use Composer\CaBundle\CaBundle;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Message\StreamInterface;

/**
 * Class to fetch html pages
 */
final class CurlDispatcher
{
    private static int $contentLengthThreshold = 5000000;

    private RequestInterface $request;
    private StreamFactoryInterface $streamFactory;
    private $curl;
    private $result;
    private array $headers = [];
    private $isBinary = false;
    private ?StreamInterface $body = null;
    private ?int $error = null;
    private array $settings;

    /**
     * @return ResponseInterface[]
     */
    public static function fetch(array $settings, ResponseFactoryInterface $responseFactory, RequestInterface ...$requests): array
    {
        if (count($requests) === 1) {
            $connection = new static($settings, $requests[0]);
            curl_exec($connection->curl);
            return [$connection->getResponse($responseFactory)];
        }

        //Init connections
        $multi = curl_multi_init();
        $connections = [];

        foreach ($requests as $request) {
            $connection = new static($settings, $request);
            curl_multi_add_handle($multi, $connection->curl);

            $connections[] = $connection;
        }

        //Run
        $active = null;
        do {
            $status = curl_multi_exec($multi, $active);

            if ($active) {
                curl_multi_select($multi);
            }

            $info = curl_multi_info_read($multi);

            if ($info) {
                foreach ($connections as $connection) {
                    if ($connection->curl === $info['handle']) {
                        $connection->result = $info['result'];
                        break;
                    }
                }
            }
        } while ($active && $status == CURLM_OK);

        //Close connections
        foreach ($connections as $connection) {
            curl_multi_remove_handle($multi, $connection->curl);
        }

        curl_multi_close($multi);

        return array_map(
            fn ($connection) => $connection->getResponse($responseFactory),
            $connections
        );
    }

    private function __construct(array $settings, RequestInterface $request, ?StreamFactoryInterface $streamFactory = null)
    {
        $this->request = $request;
        $this->curl = curl_init((string) $request->getUri());
        $this->settings = $settings;
        $this->streamFactory = $streamFactory ?? FactoryDiscovery::getStreamFactory();

        $cookies = $settings['cookies_path'] ?? str_replace('//', '/', sys_get_temp_dir().'/embed-cookies.txt');

        curl_setopt_array($this->curl, [
            CURLOPT_HTTPHEADER => $this->getRequestHeaders(),
            CURLOPT_POST => strtoupper($request->getMethod()) === 'POST',
            CURLOPT_MAXREDIRS => $settings['max_redirs'] ?? 10,
            CURLOPT_CONNECTTIMEOUT => $settings['connect_timeout'] ?? 10,
            CURLOPT_TIMEOUT => $settings['timeout'] ?? 10,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYHOST => $settings['ssl_verify_host'] ?? 0,
            CURLOPT_SSL_VERIFYPEER => $settings['ssl_verify_peer'] ?? false,
            CURLOPT_ENCODING => '',
            CURLOPT_CAINFO => CaBundle::getSystemCaRootBundlePath(),
            CURLOPT_AUTOREFERER => true,
            CURLOPT_FOLLOWLOCATION => $settings['follow_location'] ?? true,
            CURLOPT_IPRESOLVE => CURL_IPRESOLVE_V4,
            CURLOPT_USERAGENT => $settings['user_agent'] ?? $request->getHeaderLine('User-Agent'),
            CURLOPT_COOKIEJAR => $cookies,
            CURLOPT_COOKIEFILE => $cookies,
            CURLOPT_HEADERFUNCTION => [$this, 'writeHeader'],
            CURLOPT_WRITEFUNCTION => [$this, 'writeBody'],
        ]);
    }

    private function getResponse(ResponseFactoryInterface $responseFactory): ResponseInterface
    {
        $info = curl_getinfo($this->curl);

        if ($this->error) {
            $this->error(curl_strerror($this->error), $this->error);
        }

        if (curl_errno($this->curl)) {
            $this->error(curl_error($this->curl), curl_errno($this->curl));
        }

        curl_close($this->curl);

        if ($info['http_code'] === 999) {
            $info['http_code'] = 403;
        }
        $response = $responseFactory->createResponse($info['http_code']);

        foreach ($this->headers as $header) {
            list($name, $value) = $header;
            $response = $response->withAddedHeader($name, $value);
        }

        $response = $response
            ->withAddedHeader('Content-Location', $info['url'])
            ->withAddedHeader('X-Request-Time', sprintf('%.3f ms', $info['total_time']));

        if ($this->body) {
            //5Mb max
            $this->body->rewind();
            $response = $response->withBody($this->body);
            $this->body = null;
        }

        return $response;
    }

    private function error(string $message, int $code)
    {
        $ignored = $this->settings['ignored_errors'] ?? null;

        if ($ignored === true || (is_array($ignored) && in_array($code, $ignored))) {
            return;
        }

        if ($this->isBinary && $code === CURLE_WRITE_ERROR) {
            // The write callback aborted the request to prevent a download of the binary file
            return;
        }

        throw new NetworkException($message, $code, $this->request);
    }

    private function getRequestHeaders(): array
    {
        $headers = [];

        foreach ($this->request->getHeaders() as $name => $values) {
            switch (strtolower($name)) {
                case 'user-agent':
                break;
                default:
                $headers[] = $name . ':' . implode(', ', $values);
            }
        }

        return $headers;
    }

    private function writeHeader($curl, $string): int
    {
        if (preg_match('/^([\w-]+):(.*)$/', $string, $matches)) {
            $name = strtolower($matches[1]);
            $value = trim($matches[2]);
            $this->headers[] = [$name, $value];

            if ($name === 'content-type') {
                $this->isBinary = !preg_match('/(text|html|json)/', strtolower($value));
            }
        } elseif ($this->headers) {
            $key = array_key_last($this->headers);
            $this->headers[$key][1] .= ' '.trim($string);
        }

        return strlen($string);
    }

    private function writeBody($curl, $string): int
    {
        if ($this->isBinary) {
            return -1;
        }

        if (!$this->body) {
            $this->body = $this->streamFactory->createStreamFromFile('php://temp', 'w+');
        }

        if ($this->body->getSize() > self::$contentLengthThreshold) {
            return strlen($string);
        }

        return $this->body->write($string);
    }
}
