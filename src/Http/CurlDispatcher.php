<?php
declare(strict_types = 1);

namespace Embed\Http;

use Composer\CaBundle\CaBundle;
use function Embed\resolveUri;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UriFactoryInterface;

/**
 * Class to fetch html pages
 *
 * Redirects are followed here, not by libcurl, so every hop is checked against
 * the URL policy and pinned to the addresses that were validated.
 *
 * @phpstan-type CurlResource resource|\CurlHandle
 */
final class CurlDispatcher
{
    private const REDIRECT_STATUSES = [301, 302, 303, 307, 308];

    private static int $contentLengthThreshold = 5000000;

    private RequestInterface $request;
    private StreamFactoryInterface $streamFactory;
    private UriFactoryInterface $uriFactory;
    private UrlPolicy $policy;
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
    /** @var array<int, string> */
    private array $pinnedIps;
    private bool $proxy;
    private bool $follow;
    private int $maxRedirs;
    private ?float $deadline;
    private bool $prereqBlocked = false;
    private bool $closed = false;
    private int $followed = 0;

    /**
     * @param array<string, mixed> $settings
     * @return ResponseInterface[]
     */
    public static function fetch(array $settings, ResponseFactoryInterface $responseFactory, RequestInterface ...$requests): array
    {
        if ($requests === []) {
            return [];
        }

        $policy = self::policy($settings);
        $proxy = self::environmentProxyEnabled();
        $pins = [];
        foreach ($requests as $request) {
            $pins[] = $policy->validate($request);
        }

        if (count($requests) === 1) {
            $connection = new static($settings, $requests[0], $pins[0], $policy, $proxy);
            try {
                $connection->execute();

                return [$connection->getResponse($responseFactory)];
            } catch (\Throwable $exception) {
                $connection->closeCurl();
                throw $exception;
            }
        }

        return self::fetchMulti($settings, $responseFactory, array_values($requests), $pins, $policy, $proxy);
    }

    /**
     * @param array<string, mixed> $settings
     */
    private static function policy(array $settings): UrlPolicy
    {
        $policy = $settings['url_policy'] ?? null;
        if ($policy instanceof UrlPolicy) {
            return $policy;
        }

        return UrlPolicy::default();
    }

    private static function environmentProxyEnabled(): bool
    {
        foreach (['http_proxy', 'HTTP_PROXY', 'https_proxy', 'HTTPS_PROXY', 'all_proxy', 'ALL_PROXY'] as $name) {
            $value = getenv($name);
            if (is_string($value) && trim($value) !== '') {
                return true;
            }
        }

        return false;
    }

    /**
     * @param array<string, mixed> $settings
     * @param array<int, RequestInterface> $requests
     * @param array<int, array<int, string>> $pins
     * @return ResponseInterface[]
     */
    private static function fetchMulti(
        array $settings,
        ResponseFactoryInterface $responseFactory,
        array $requests,
        array $pins,
        UrlPolicy $policy,
        bool $proxy
    ): array {
        //Init connections
        $multi = curl_multi_init();
        // curl_multi_init() can fail on PHP 7.4. Current stubs type it as CurlMultiHandle only.
        /** @phpstan-ignore identical.alwaysFalse */
        if ($multi === false) {
            throw new NetworkException('Unable to initialize curl', 0, $requests[0]);
        }
        $connections = [];

        try {
            foreach ($requests as $index => $request) {
                $connection = new static($settings, $request, $pins[$index], $policy, $proxy);
                $connections[] = $connection;
                /** @var resource|\CurlHandle $curlHandle */
                $curlHandle = $connection->curl;
                /** @phpstan-ignore argument.type (PHP 7.4/8.0 compatibility) */
                curl_multi_add_handle($multi, $curlHandle);
            }

            //Run
            self::pump($multi, $connections);
        } catch (\Throwable $exception) {
            self::releaseMulti($multi, $connections, true);
            throw $exception;
        }

        //Close connections
        self::releaseMulti($multi, $connections, false);

        $responses = [];
        foreach ($connections as $connection) {
            $responses[] = $connection->getResponse($responseFactory);
        }

        return $responses;
    }

    /**
     * On PHP 7.4 $multi is a resource. PHP 8 uses CurlMultiHandle.
     *
     * @param resource|\CurlMultiHandle $multi
     * @param array<int, self> $connections
     */
    private static function pump($multi, array $connections): void
    {
        $active = 0;
        $status = CURLM_OK;

        do {
            do {
                /** @phpstan-ignore argument.type (PHP 7.4/8.0 compatibility) */
                $status = curl_multi_exec($multi, $active);
            } while ($status === CURLM_CALL_MULTI_PERFORM);

            $requeued = false;
            while (true) {
                /** @phpstan-ignore argument.type (PHP 7.4/8.0 compatibility) */
                $info = curl_multi_info_read($multi);
                if (!is_array($info)) {
                    break;
                }

                $result = $info['result'] ?? null;
                $handle = $info['handle'] ?? null;
                if (!is_int($result)) {
                    continue;
                }

                foreach ($connections as $connection) {
                    if ($connection->curl !== $handle) {
                        continue;
                    }
                    if (!$connection->finishTransfer($result)) {
                        break;
                    }

                    $curlHandle = $connection->curl;
                    /** @phpstan-ignore argument.type, argument.type (PHP 7.4/8.0 compatibility) */
                    curl_multi_remove_handle($multi, $curlHandle);
                    /** @phpstan-ignore argument.type, argument.type (PHP 7.4/8.0 compatibility) */
                    curl_multi_add_handle($multi, $curlHandle);
                    $requeued = true;
                    break;
                }
            }

            if ($requeued) {
                $active = 1;
            }

            if ($active !== 0 && $status === CURLM_OK) {
                /** @phpstan-ignore argument.type (PHP 7.4/8.0 compatibility) */
                $selected = curl_multi_select($multi, 1.0);
                if ($selected === -1) {
                    usleep(10000);
                }
            }
        } while ($active !== 0 && $status === CURLM_OK);
    }

    /**
     * On PHP 7.4 $multi is a resource. PHP 8 uses CurlMultiHandle.
     *
     * @param resource|\CurlMultiHandle $multi
     * @param array<int, self> $connections
     */
    private static function releaseMulti($multi, array $connections, bool $closeHandles): void
    {
        foreach ($connections as $connection) {
            /** @var resource|\CurlHandle $curlHandle */
            $curlHandle = $connection->curl;
            /** @phpstan-ignore argument.type, argument.type (PHP 7.4/8.0 compatibility) */
            curl_multi_remove_handle($multi, $curlHandle);
            if ($closeHandles) {
                $connection->closeCurl();
            }
        }

        if (PHP_VERSION_ID < 80000) {
            /** @phpstan-ignore argument.type (PHP 7.4/8.0 compatibility) */
            curl_multi_close($multi);
        }
    }

    /**
     * @param array<string, mixed> $settings
     * @param array<int, string> $pinnedIps
     */
    private function __construct(array $settings, RequestInterface $request, array $pinnedIps, UrlPolicy $policy, bool $proxy)
    {
        $curl = curl_init();
        if ($curl === false) {
            throw new NetworkException('Unable to initialize curl', 0, $request);
        }

        $this->request = $request;
        $this->settings = $settings;
        $this->pinnedIps = $pinnedIps;
        $this->policy = $policy;
        $this->proxy = $proxy;
        $this->follow = $this->settingBool('follow_location', true);
        $this->maxRedirs = $this->redirectLimit();
        $timeout = $this->settingInt('timeout', 10);
        // libcurl's CURLOPT_TIMEOUT covers the whole transfer, including
        // redirects. Each hop receives only the time that is still left.
        $this->deadline = $timeout > 0 ? microtime(true) + $timeout : null;
        $this->streamFactory = FactoryDiscovery::getStreamFactory();
        $this->uriFactory = FactoryDiscovery::getUriFactory();
        $this->curl = $curl;

        $this->applyBaseOptions();
        $this->applyToHandle();
    }

    private function execute(): void
    {
        while (true) {
            $this->resetExchange();
            if ($this->timeoutExpired()) {
                $this->error = CURLE_OPERATION_TIMEDOUT;

                return;
            }
            $this->applyTimeout();
            $this->execOnce();
            $this->guardConnection();

            if (!$this->shouldFollowRedirect()) {
                return;
            }

            if ($this->followed >= $this->maxRedirs) {
                $this->error = CURLE_TOO_MANY_REDIRECTS;

                return;
            }

            $next = $this->redirectRequest();
            if ($next === null) {
                return;
            }

            $this->request = $next;
            $this->pinnedIps = $this->policy->validate($next);
            $this->applyToHandle();
            ++$this->followed;
        }
    }

    /**
     * @return bool true when the handle was updated and must be run again
     */
    private function finishTransfer(int $result): bool
    {
        if ($this->prereqBlocked) {
            throw new BlockedRequestException(
                'The URL resolves to a non-public address.',
                $this->request,
                'non_public_address'
            );
        }

        if ($result !== CURLE_OK && !($this->isBinary && $result === CURLE_WRITE_ERROR)) {
            $this->error = $result;

            return false;
        }

        $this->guardPrimaryIp();

        if (!$this->shouldFollowRedirect()) {
            return false;
        }

        if ($this->followed >= $this->maxRedirs) {
            $this->error = CURLE_TOO_MANY_REDIRECTS;

            return false;
        }

        if ($this->timeoutExpired()) {
            $this->error = CURLE_OPERATION_TIMEDOUT;

            return false;
        }

        $next = $this->redirectRequest();
        if ($next === null) {
            return false;
        }

        $this->request = $next;
        // DNS for this hop runs here, on the multi thread, so the other
        // transfers in the batch wait until validate() returns.
        $this->pinnedIps = $this->policy->validate($next);
        $this->applyToHandle();
        $this->resetExchange();
        ++$this->followed;

        return true;
    }

    private function execOnce(): void
    {
        /** @var resource|\CurlHandle $curlHandle */
        $curlHandle = $this->curl;
        /** @phpstan-ignore argument.type (PHP 7.4/8.0 compatibility) */
        curl_exec($curlHandle);
    }

    private function guardConnection(): void
    {
        if ($this->prereqBlocked) {
            throw new BlockedRequestException(
                'The URL resolves to a non-public address.',
                $this->request,
                'non_public_address'
            );
        }

        $this->guardPrimaryIp();

        $curlHandle = $this->curl;
        /** @phpstan-ignore argument.type (PHP 7.4/8.0 compatibility) */
        $errno = curl_errno($curlHandle);
        if ($errno === 0 || ($this->isBinary && $errno === CURLE_WRITE_ERROR)) {
            return;
        }

        /** @phpstan-ignore argument.type (PHP 7.4/8.0 compatibility) */
        $this->error(curl_error($curlHandle), $errno);
    }

    private function guardPrimaryIp(): void
    {
        if ($this->proxy) {
            return;
        }

        $curlHandle = $this->curl;
        /** @phpstan-ignore argument.type (PHP 7.4/8.0 compatibility) */
        $ip = curl_getinfo($curlHandle, CURLINFO_PRIMARY_IP);
        if ($ip === '') {
            return;
        }

        if ($this->addressIsPinned($ip)) {
            return;
        }

        throw new BlockedRequestException(
            'The URL resolves to a non-public address.',
            $this->request,
            'non_public_address'
        );
    }

    private function shouldFollowRedirect(): bool
    {
        if (!$this->follow) {
            return false;
        }

        return in_array($this->httpStatus(), self::REDIRECT_STATUSES, true);
    }

    private function redirectRequest(): ?RequestInterface
    {
        $location = null;
        foreach ($this->headers as $header) {
            if ($header[0] === 'location') {
                $location = $header[1];
            }
        }

        if ($location === null) {
            return null;
        }

        $location = trim($location);
        if ($location === '') {
            return null;
        }

        if (preg_match('/[\x00-\x1f\x7f]/', $location) === 1) {
            throw new BlockedRequestException(
                'The redirect target is not allowed.',
                $this->request,
                'redirect'
            );
        }

        try {
            $target = $this->uriFactory->createUri($location);
        } catch (\InvalidArgumentException $exception) {
            throw new BlockedRequestException(
                'The redirect target is not allowed.',
                $this->request,
                'redirect'
            );
        }

        $current = $this->request->getUri();
        $resolved = resolveUri($current, $target);
        $method = $this->methodAfterRedirect($this->httpStatus());
        $request = $this->request->withMethod($method)->withUri($resolved);

        if ($method === 'GET' || $method === 'HEAD') {
            $request = $request
                ->withBody($this->streamFactory->createStream(''))
                ->withoutHeader('Content-Type')
                ->withoutHeader('Content-Length');
        }

        $currentHost = strtolower($this->bareHost($current->getHost()));
        $nextHost = strtolower($this->bareHost($resolved->getHost()));
        if ($currentHost !== $nextHost) {
            $request = $request->withoutHeader('Authorization')->withoutHeader('Cookie');
        }

        return $request->withHeader('Referer', (string) $current);
    }

    private function methodAfterRedirect(int $status): string
    {
        $method = strtoupper($this->request->getMethod());
        if ($status === 303) {
            return $method === 'HEAD' ? 'HEAD' : 'GET';
        }

        if (($status === 301 || $status === 302) && $method === 'POST') {
            return 'GET';
        }

        return $method;
    }

    private function bareHost(string $host): string
    {
        if (strlen($host) >= 2 && $host[0] === '[' && substr($host, -1) === ']') {
            return substr($host, 1, -1);
        }

        return $host;
    }

    private function addressIsPinned(string $ip): bool
    {
        foreach ($this->pinnedIps as $pinned) {
            if (IpClassifier::equals($pinned, $ip)) {
                return true;
            }
        }

        return false;
    }

    private function applyBaseOptions(): void
    {
        $cookies = $this->cookiesPath();
        $caBundle = CaBundle::getSystemCaRootBundlePath();

        $this->setopt(CURLOPT_CONNECTTIMEOUT, $this->settingInt('connect_timeout', 10));
        $this->setopt(CURLOPT_RETURNTRANSFER, true);
        $this->setopt(CURLOPT_SSL_VERIFYHOST, $this->sslVerifyHost());
        $this->setopt(CURLOPT_SSL_VERIFYPEER, $this->sslVerifyPeer());
        $this->setopt(CURLOPT_ENCODING, '');
        if ($caBundle !== '') {
            $this->setopt(CURLOPT_CAINFO, $caBundle);
        }
        $this->setopt(CURLOPT_FOLLOWLOCATION, false);
        $this->setopt(CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
        $this->setopt(CURLOPT_USERAGENT, $this->userAgent());
        $this->setopt(CURLOPT_COOKIEJAR, $cookies);
        $this->setopt(CURLOPT_COOKIEFILE, $cookies);
        $this->setopt(CURLOPT_HEADERFUNCTION, [$this, 'writeHeader']);
        $this->setopt(CURLOPT_WRITEFUNCTION, [$this, 'writeBody']);
        $this->applyProtocolLimits();
        $this->applyPrereq();
    }

    private function applyProtocolLimits(): void
    {
        if (defined('CURLOPT_PROTOCOLS_STR')) {
            $this->setopt(CURLOPT_PROTOCOLS_STR, 'HTTP,HTTPS');
            $this->setopt(CURLOPT_REDIR_PROTOCOLS_STR, 'HTTP,HTTPS');

            return;
        }

        $this->setopt(CURLOPT_PROTOCOLS, CURLPROTO_HTTP | CURLPROTO_HTTPS);
        $this->setopt(CURLOPT_REDIR_PROTOCOLS, CURLPROTO_HTTP | CURLPROTO_HTTPS);
    }

    private function applyPrereq(): void
    {
        if (PHP_VERSION_ID < 80400 || !defined('CURLOPT_PREREQFUNCTION')) {
            return;
        }

        $this->setopt(CURLOPT_PREREQFUNCTION, [$this, 'approveConnection']);
    }

    /**
     * Called by libcurl on PHP 8.4+ before the request is sent.
     *
     * @param resource|\CurlHandle $curl
     */
    public function approveConnection($curl, string $primaryIp, string $localIp, int $primaryPort, int $localPort): int
    {
        if ($this->proxy || $this->addressIsPinned($primaryIp)) {
            return CURL_PREREQFUNC_OK;
        }

        $this->prereqBlocked = true;

        return CURL_PREREQFUNC_ABORT;
    }

    private function applyToHandle(): void
    {
        $this->setopt(CURLOPT_URL, (string) $this->request->getUri());
        $this->setopt(CURLOPT_HTTPHEADER, $this->getRequestHeaders());

        $this->applyTimeout();

        $method = strtoupper($this->request->getMethod());
        if ($method === 'POST') {
            $this->setopt(CURLOPT_POST, true);
            $this->setopt(CURLOPT_POSTFIELDS, (string) $this->request->getBody());
        } elseif ($method === 'HEAD') {
            $this->setopt(CURLOPT_NOBODY, true);
            $this->setopt(CURLOPT_HTTPGET, true);
        } else {
            $this->setopt(CURLOPT_HTTPGET, true);
            $this->setopt(CURLOPT_POST, false);
        }

        $this->applyResolve();
    }

    private function applyResolve(): void
    {
        if ($this->proxy || $this->pinnedIps === []) {
            return;
        }

        $uri = $this->request->getUri();
        $host = $this->policy->curlResolveHost($uri->getHost());
        $port = $uri->getPort();
        if ($port === null) {
            $port = strtolower($uri->getScheme()) === 'https' ? 443 : 80;
        }

        $ips = $this->pinnedIps;
        $version = curl_version();
        $curlVersion = is_array($version) && isset($version['version']) && is_string($version['version'])
            ? $version['version']
            : '0';
        if (version_compare($curlVersion, '7.59.0', '<')) {
            $ips = [$ips[0]];
        }

        $formatted = [];
        foreach ($ips as $ip) {
            $formatted[] = strpos($ip, ':') !== false ? '['.$ip.']' : $ip;
        }

        $resolveHost = strpos($host, ':') !== false ? '['.$host.']' : $host;
        $this->setopt(CURLOPT_RESOLVE, [$resolveHost.':'.$port.':'.implode(',', $formatted)]);
    }

    private function getResponse(ResponseFactoryInterface $responseFactory): ResponseInterface
    {
        try {
            return $this->buildResponse($responseFactory);
        } finally {
            $this->closeCurl();
        }
    }

    private function buildResponse(ResponseFactoryInterface $responseFactory): ResponseInterface
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
        if ($errno !== 0 && !($this->isBinary && $errno === CURLE_WRITE_ERROR)) {
            /** @phpstan-ignore argument.type (PHP 7.4/8.0 compatibility) */
            $this->error(curl_error($curlHandle), $errno);
        }

        $response = $responseFactory->createResponse($info['http_code']);

        foreach ($this->headers as $header) {
            list($name, $value) = $header;
            $response = $response->withAddedHeader($name, $value);
        }

        $effective = $info['url'] !== '' ? $info['url'] : (string) $this->request->getUri();

        $response = $response
            ->withAddedHeader('Content-Location', $effective)
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

    private function closeCurl(): void
    {
        if ($this->closed || PHP_VERSION_ID >= 80000) {
            $this->closed = true;

            return;
        }

        $this->closed = true;
        $curlHandle = $this->curl;
        /** @phpstan-ignore argument.type (PHP 7.4/8.0 compatibility) */
        curl_close($curlHandle);
    }

    private function resetExchange(): void
    {
        $this->headers = [];
        $this->body = null;
        $this->isBinary = false;
        $this->error = null;
        $this->prereqBlocked = false;
    }

    private function httpStatus(): int
    {
        $curlHandle = $this->curl;
        /** @phpstan-ignore argument.type (PHP 7.4/8.0 compatibility) */
        return curl_getinfo($curlHandle, CURLINFO_HTTP_CODE);
    }

    /**
     * @param mixed $value
     */
    private function setopt(int $option, $value): void
    {
        $curlHandle = $this->curl;
        /** @phpstan-ignore argument.type (PHP 7.4/8.0 compatibility) */
        curl_setopt($curlHandle, $option, $value);
    }

    private function settingInt(string $key, int $default): int
    {
        $value = $this->settings[$key] ?? $default;
        if (is_int($value)) {
            return $value;
        }
        if (is_float($value) || (is_string($value) && is_numeric($value))) {
            return (int) $value;
        }

        return $default;
    }

    private function settingBool(string $key, bool $default): bool
    {
        $value = $this->settings[$key] ?? $default;
        if (is_bool($value)) {
            return $value;
        }
        if ($value === 1 || $value === '1') {
            return true;
        }
        if ($value === 0 || $value === '0') {
            return false;
        }

        return $default;
    }

    /**
     * libcurl accepts only 0 and 2. PHP turns 1 into 2 and emits a notice,
     * which is what a truthy setting used to do when it was passed through.
     *
     * @return 0|2
     */
    private function sslVerifyHost(): int
    {
        $value = $this->settings['ssl_verify_host'] ?? 0;
        if ($value === false || $value === 0 || $value === 0.0 || $value === '' || $value === '0') {
            return 0;
        }

        return 2;
    }

    /**
     * A negative max_redirs is curl's "unlimited". Cap it so a redirect loop
     * cannot run without a bound now that we follow hops ourselves.
     */
    private function redirectLimit(): int
    {
        $limit = $this->settingInt('max_redirs', 10);
        if ($limit < 0) {
            return 50;
        }

        return $limit;
    }

    private function timeoutExpired(): bool
    {
        return $this->deadline !== null && microtime(true) >= $this->deadline;
    }

    private function applyTimeout(): void
    {
        if ($this->deadline === null) {
            $this->setopt(CURLOPT_TIMEOUT, 0);

            return;
        }

        $remainingMs = (int) floor(($this->deadline - microtime(true)) * 1000);
        if ($remainingMs < 1) {
            $remainingMs = 1;
        }

        $this->setopt(CURLOPT_TIMEOUT_MS, $remainingMs);
    }

    /**
     * Same rule as a value passed straight to curl: only a falsy setting
     * disables certificate verification. 2, "true" and "yes" stay enabled.
     */
    private function sslVerifyPeer(): bool
    {
        $value = $this->settings['ssl_verify_peer'] ?? false;
        if ($value === false || $value === 0 || $value === 0.0 || $value === '' || $value === '0' || $value === []) {
            return false;
        }

        return true;
    }

    private function userAgent(): string
    {
        $value = $this->settings['user_agent'] ?? $this->request->getHeaderLine('User-Agent');
        if (is_string($value) && $value !== '') {
            return $value;
        }

        return 'Embed/curl';
    }

    private function cookiesPath(): string
    {
        $value = $this->settings['cookies_path'] ?? null;
        if (is_string($value) && $value !== '') {
            return $value;
        }

        return str_replace('//', '/', sys_get_temp_dir().'/embed-cookies.txt');
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
