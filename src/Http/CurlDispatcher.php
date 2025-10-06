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
 *
 * @phpstan-type CurlResource resource|\CurlHandle
 */
final class CurlDispatcher
{
    private static int $contentLengthThreshold = 5000000;

    private RequestInterface $request;
    private StreamFactoryInterface $streamFactory;
    /**
     * @var resource|\CurlHandle
     * @phpstan-ignore property.unusedType (resource type needed for PHP 7.4 compatibility)
     */
    private $curl;
    /** @var array<array{0: string, 1: string}> */
    private array $headers = [];
    private bool $isBinary = false;
    private ?StreamInterface $body = null;
    private ?int $error = null;
    /** @var array<string, mixed> */
    private array $settings;

    /**
     * @param array<string, mixed> $settings
     * @return ResponseInterface[]
     */
    public static function fetch(array $settings, ResponseFactoryInterface $responseFactory, RequestInterface ...$requests): array
    {
        if (count($requests) === 1) {
            $connection = new static($settings, $requests[0]);
            /** @var resource|\CurlHandle $curlHandle */
            $curlHandle = $connection->curl;
            /** @phpstan-ignore argument.type (PHP 7.4/8.0 compatibility) */
            curl_exec($curlHandle);
            return [$connection->getResponse($responseFactory)];
        }

        //Init connections
        $multi = curl_multi_init();
        $connections = [];

        foreach ($requests as $request) {
            $connection = new static($settings, $request);
            /** @var resource|\CurlHandle $curlHandle */
            $curlHandle = $connection->curl;
            /** @phpstan-ignore argument.type (PHP 7.4/8.0 compatibility) */
            curl_multi_add_handle($multi, $curlHandle);

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

            if (is_array($info) && isset($info['handle'], $info['result'])) {
                $result = $info['result'];
                // Validate and cast result to int, only set if it's a non-success error code
                if (is_numeric($result)) {
                    $errorCode = (int) $result;
                    if ($errorCode !== CURLE_OK) {
                        foreach ($connections as $connection) {
                            if ($connection->curl === $info['handle']) {
                                $connection->error = $errorCode;
                                break;
                            }
                        }
                    }
                }
            }
        } while ($active && $status === CURLM_OK);

        //Close connections
        foreach ($connections as $connection) {
            /** @var resource|\CurlHandle $curlHandle */
            $curlHandle = $connection->curl;
            /** @phpstan-ignore argument.type (PHP 7.4/8.0 compatibility) */
            curl_multi_remove_handle($multi, $curlHandle);
        }

        curl_multi_close($multi);

        return array_map(
            fn ($connection) => $connection->getResponse($responseFactory),
            $connections
        );
    }

    /**
     * @param array<string, mixed> $settings
     */
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
        /** @var resource|\CurlHandle $curlHandle */
        $curlHandle = $this->curl;
        /** @phpstan-ignore argument.type (PHP 7.4/8.0 compatibility) */
        $info = curl_getinfo($curlHandle);

        if ($this->error !== null && $this->error !== 0) {
            /** @phpstan-ignore argument.type (curl_strerror returns string|null in some versions) */
            $this->error(curl_strerror($this->error), $this->error);
        }

        /** @phpstan-ignore argument.type (PHP 7.4/8.0 compatibility) */
        $errno = curl_errno($curlHandle);
        if ($errno !== 0) {
            /** @phpstan-ignore argument.type (PHP 7.4/8.0 compatibility) */
            $this->error(curl_error($curlHandle), $errno);
        }

        /** @phpstan-ignore argument.type (PHP 7.4/8.0 compatibility) */
        curl_close($curlHandle);

        $response = $responseFactory->createResponse($info['http_code']);

        foreach ($this->headers as $header) {
            list($name, $value) = $header;
            $response = $response->withAddedHeader($name, $value);
        }

        $response = $response
            ->withAddedHeader('Content-Location', $info['url'])
            ->withAddedHeader('X-Request-Time', sprintf('%.3f ms', $info['total_time']));

        if ($this->body !== null) {
            //5Mb max
            $this->body->rewind();
            $response = $response->withBody($this->body);
            $this->body = null;
        }

        return $response;
    }

    private function error(string $message, int $code): void
    {
        $ignored = $this->settings['ignored_errors'] ?? null;

        if ($ignored === true || (is_array($ignored) && in_array($code, $ignored, true))) {
            return;
        }

        if ($this->isBinary && $code === CURLE_WRITE_ERROR) {
            // The write callback aborted the request to prevent a download of the binary file
            return;
        }

        throw new NetworkException($message, $code, $this->request);
    }

    /**
     * @return array<string>
     */
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

    /**
     * @param resource|\CurlHandle $curl
     * @param mixed $string
     */
    private function writeHeader($curl, $string): int
    {
        if (!is_string($string)) {
            return 0;
        }

        if (preg_match('/^([\w-]+):(.*)$/', $string, $matches) === 1) {
            $name = strtolower($matches[1]);
            $value = trim($matches[2]);
            $this->headers[] = [$name, $value];

            if ($name === 'content-type') {
                $this->isBinary = preg_match('/(text|html|json)/', strtolower($value)) === 0;
            }
        } elseif ($this->headers !== []) {
            $key = array_key_last($this->headers);
            $this->headers[$key][1] .= ' '.trim($string);
        }

        return strlen($string);
    }

    /**
     * @param resource|\CurlHandle $curl
     * @param mixed $string
     */
    private function writeBody($curl, $string): int
    {
        if (!is_string($string)) {
            return -1;
        }

        if ($this->isBinary) {
            return -1;
        }

        if ($this->body === null) {
            $this->body = $this->streamFactory->createStreamFromFile('php://temp', 'w+');
        }

        if ($this->body->getSize() > self::$contentLengthThreshold) {
            return strlen($string);
        }

        return $this->body->write($string);
    }
}
