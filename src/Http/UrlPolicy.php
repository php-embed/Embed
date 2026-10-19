<?php
declare(strict_types = 1);

namespace Embed\Http;

use Psr\Http\Message\RequestInterface;

/**
 * Decides whether an absolute http(s) URI may be requested.
 *
 * The policy is immutable. Each wither returns a new instance.
 * Hostnames are resolved to IPv4 addresses, matching CurlDispatcher, which
 * forces CURL_IPRESOLVE_V4. IP literals are classified without DNS, including
 * the alternative spellings libcurl accepts.
 */
final class UrlPolicy
{
    private bool $allowPrivate;

    /** @var array<int, string> */
    private array $allowedHosts;

    /** @var array<int, int>|null */
    private ?array $allowedPorts;

    /**
     * @var callable(string): array<int, string>|null
     */
    private $resolver;

    /**
     * @param array<int, string>                        $allowedHosts
     * @param array<int, int>|null                      $allowedPorts
     * @param callable(string): array<int, string>|null $resolver
     */
    private function __construct(bool $allowPrivate, array $allowedHosts, ?array $allowedPorts, $resolver)
    {
        $this->allowPrivate = $allowPrivate;
        $this->allowedHosts = $allowedHosts;
        $this->allowedPorts = $allowedPorts;
        $this->resolver = $resolver;
    }

    public static function default(): self
    {
        return new self(false, [], null, null);
    }

    /**
     * Allow addresses that are not public (loopback, RFC 1918, link-local, ...).
     * Use this for intranet installs.
     */
    public function allowPrivateNetworks(): self
    {
        $copy = clone $this;
        $copy->allowPrivate = true;

        return $copy;
    }

    /**
     * Hosts that may resolve to a non-public address.
     * A pattern "*.corp" matches any name ending in ".corp".
     */
    public function withAllowedHosts(string ...$hosts): self
    {
        $copy = clone $this;
        $copy->allowedHosts = array_values($hosts);

        return $copy;
    }

    /**
     * Restrict the effective port (80 and 443 when the URI omits one).
     * Passing no restriction is the default: every port is allowed.
     *
     * @param array<mixed> $ports
     */
    public function withAllowedPorts(array $ports): self
    {
        $normalized = [];
        foreach ($ports as $port) {
            if (!is_int($port)) {
                throw new \InvalidArgumentException('Allowed ports must be a list of integers.');
            }
            $normalized[] = $port;
        }

        $copy = clone $this;
        $copy->allowedPorts = $normalized;

        return $copy;
    }

    /**
     * Replace DNS. The callable receives an ASCII hostname and returns IPv4 strings.
     * An empty list is treated as a DNS failure.
     *
     * @param callable(string): array<int, string> $resolver
     */
    public function withResolver(callable $resolver): self
    {
        $copy = clone $this;
        $copy->resolver = $resolver;

        return $copy;
    }

    /**
     * @throws BlockedRequestException
     * @throws NetworkException        when the hostname cannot be resolved
     * @return array<int,              string> addresses that were accepted, in lookup order
     */
    public function validate(RequestInterface $request): array
    {
        $uri = $request->getUri();
        $scheme = strtolower($uri->getScheme());

        if ($scheme !== 'http' && $scheme !== 'https') {
            throw $this->blocked($request, 'scheme');
        }

        if (strpos((string) $uri, '\\') !== false || $uri->getUserInfo() !== '') {
            throw $this->blocked($request, 'host');
        }

        $host = $this->host($uri->getHost());
        if ($host === null) {
            throw $this->blocked($request, 'host');
        }

        $port = $uri->getPort();
        if ($port === 0) {
            throw $this->blocked($request, 'port');
        }

        if ($this->allowedPorts !== null) {
            $effective = $port ?? ($scheme === 'https' ? 443 : 80);
            if (!in_array($effective, $this->allowedPorts, true)) {
                throw $this->blocked($request, 'port');
            }
        }

        $literal = IpClassifier::canonical($host);
        if ($literal !== null) {
            $ips = [$literal];
            $lookup = $host;
        } else {
            $lookup = $this->asciiHost($request, $host);
            $ips = $this->resolve($request, $lookup);
        }

        // One non-public address blocks the whole lookup. An allowlisted
        // address does not exempt the others returned alongside it.
        if (!$this->allowPrivate && !$this->hostAllowed($host) && !$this->hostAllowed($lookup)) {
            foreach ($ips as $ip) {
                if (!IpClassifier::isPublic($ip) && !$this->hostAllowed($ip)) {
                    throw $this->blocked($request, 'non_public_address');
                }
            }
        }

        return $ips;
    }

    /**
     * Host libcurl will look up, so a CURLOPT_RESOLVE entry matches it.
     * Brackets are removed and internationalized names are punycode.
     * A trailing dot is preserved: curl keeps it.
     */
    public function curlResolveHost(string $host): string
    {
        if (strlen($host) >= 2 && $host[0] === '[' && substr($host, -1) === ']') {
            $host = substr($host, 1, -1);
        }

        if (preg_match('/[^\x00-\x7f]/', $host) !== 1 || !function_exists('idn_to_ascii')) {
            return $host;
        }

        $ascii = idn_to_ascii($host, IDNA_DEFAULT);
        if (!is_string($ascii) || $ascii === '') {
            return $host;
        }

        return $ascii;
    }

    private function host(string $host): ?string
    {
        if (strlen($host) >= 2 && $host[0] === '[' && substr($host, -1) === ']') {
            $host = substr($host, 1, -1);
        }

        $host = rtrim($host, '.');
        if ($host === '') {
            return null;
        }

        if (preg_match('/[\x00-\x20\x7f\\\\@\[\]]/', $host) === 1) {
            return null;
        }

        return $host;
    }

    private function asciiHost(RequestInterface $request, string $host): string
    {
        if (preg_match('/[^\x00-\x7f]/', $host) !== 1) {
            return $host;
        }

        if (!function_exists('idn_to_ascii')) {
            throw $this->blocked(
                $request,
                'host',
                'Internationalized host names require the intl extension.'
            );
        }

        $ascii = idn_to_ascii($host, IDNA_DEFAULT);
        if ($ascii === false || $ascii === '') {
            throw $this->blocked($request, 'host');
        }

        return $ascii;
    }

    /**
     * @return array<int, string>
     */
    private function resolve(RequestInterface $request, string $host): array
    {
        if ($this->resolver !== null) {
            $resolver = $this->resolver;
            $records = $resolver($host);
        } else {
            $lookedUp = gethostbynamel($host);
            $records = $lookedUp === false ? [] : $lookedUp;
        }

        $ips = [];
        foreach ($records as $record) {
            $canonical = IpClassifier::canonical($record);
            if ($canonical === null) {
                $ips = [];
                break;
            }
            if (!in_array($canonical, $ips, true)) {
                $ips[] = $canonical;
            }
        }

        if ($ips === []) {
            throw new NetworkException('Could not resolve host: '.$host, CURLE_COULDNT_RESOLVE_HOST, $request);
        }

        return $ips;
    }

    private function hostAllowed(string $host): bool
    {
        $host = mb_strtolower($host, 'UTF-8');

        foreach ($this->allowedHosts as $pattern) {
            $pattern = mb_strtolower(rtrim($pattern, '.'), 'UTF-8');
            if ($pattern === '') {
                continue;
            }

            if (strlen($pattern) > 2 && $pattern[0] === '*' && $pattern[1] === '.') {
                $suffix = substr($pattern, 1);
                if (!is_string($suffix) || $suffix === '') {
                    continue;
                }
                $hostSuffix = substr($host, -strlen($suffix));
                if (strlen($host) > strlen($suffix) && is_string($hostSuffix) && $hostSuffix === $suffix) {
                    return true;
                }
                continue;
            }

            if ($host === $pattern) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param 'scheme'|'host'|'non_public_address'|'port'|'redirect' $reason
     */
    private function blocked(RequestInterface $request, string $reason, ?string $message = null): BlockedRequestException
    {
        if ($message === null) {
            $message = self::message($reason);
        }

        return new BlockedRequestException($message, $request, $reason);
    }

    /**
     * @param 'scheme'|'host'|'non_public_address'|'port'|'redirect' $reason
     */
    private static function message(string $reason): string
    {
        if ($reason === 'scheme') {
            return 'The URL scheme is not allowed.';
        }
        if ($reason === 'host') {
            return 'The URL host is not allowed.';
        }
        if ($reason === 'non_public_address') {
            return 'The URL resolves to a non-public address.';
        }
        if ($reason === 'port') {
            return 'The URL port is not allowed.';
        }

        return 'The redirect target is not allowed.';
    }
}
