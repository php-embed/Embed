<?php
declare(strict_types = 1);

namespace Embed\Tests;

use Embed\Http\BlockedRequestException;
use Embed\Http\NetworkException;
use Embed\Http\UrlPolicy;
use InvalidArgumentException;
use Nyholm\Psr7\Factory\Psr17Factory;
use PHPUnit\Framework\TestCase;
use Psr\Http\Client\NetworkExceptionInterface;
use Psr\Http\Client\RequestExceptionInterface;
use Psr\Http\Message\RequestInterface;

class UrlPolicyTest extends TestCase
{
    public function testAcceptsPublicHostnameAndReturnsEveryAddress(): void
    {
        $policy = $this->policy(['8.8.8.8', '1.1.1.1']);

        $ips = $policy->validate($this->request('https://example.com/a'));

        $this->assertSame(['8.8.8.8', '1.1.1.1'], $ips);
    }

    public function testUnderscoreInHostnameIsAllowed(): void
    {
        $seen = null;
        $policy = UrlPolicy::default()->withResolver(function (string $host) use (&$seen): array {
            $seen = $host;

            return ['1.1.1.1'];
        });

        $ips = $policy->validate($this->request('http://my_host.example/path'));

        $this->assertSame('my_host.example', $seen);
        $this->assertSame(['1.1.1.1'], $ips);
    }

    public function testInternationalizedNameIsConvertedBeforeLookup(): void
    {
        if (!function_exists('idn_to_ascii')) {
            $this->markTestSkipped('The intl extension is required to resolve IDN hosts.');
        }

        $seen = null;
        $policy = UrlPolicy::default()->withResolver(function (string $host) use (&$seen): array {
            $seen = $host;

            return ['1.1.1.1'];
        });

        $policy->validate($this->request('http://bücher.example/'));

        $this->assertSame('xn--bcher-kva.example', $seen);
    }

    public function testMixedPublicAndPrivateAddressesAreBlocked(): void
    {
        $policy = $this->policy(['8.8.8.8', '10.0.0.1']);

        $exception = $this->assertBlocked(function () use ($policy): void {
            $policy->validate($this->request('http://example.com/'));
        }, 'non_public_address');

        $this->assertStringNotContainsString('10.0.0.1', $exception->getMessage());
        $this->assertStringNotContainsString('8.8.8.8', $exception->getMessage());
    }

    public function testDnsFailureIsANetworkException(): void
    {
        $policy = $this->policy([]);

        try {
            $policy->validate($this->request('http://missing.test/'));
            $this->fail('A DNS failure must not be reported as a successful validation.');
        } catch (BlockedRequestException $exception) {
            $this->fail('A DNS failure must stay a network error.');
        } catch (NetworkException $exception) {
            $this->assertSame(6, $exception->getCode());
            $this->assertInstanceOf(NetworkExceptionInterface::class, $exception);
            $this->assertStringNotContainsString('10.', $exception->getMessage());
        }
    }

    public function testAllowPrivateNetworksKeepsTheResolvedAddresses(): void
    {
        $policy = $this->policy(['10.1.2.3'])->allowPrivateNetworks();

        $this->assertSame(['10.1.2.3'], $policy->validate($this->request('http://intranet.test/')));
    }

    public function testAllowedHostsAndWildcardsSkipThePublicAddressCheck(): void
    {
        $policy = $this->policy(['10.0.0.5'])->withAllowedHosts('wiki.corp', '*.corp');

        $this->assertSame(['10.0.0.5'], $policy->validate($this->request('http://wiki.corp/a')));
        $this->assertSame(['10.0.0.5'], $policy->validate($this->request('http://a.b.corp/a')));

        $this->assertBlocked(function () use ($policy): void {
            $policy->validate($this->request('http://corp/a'));
        }, 'non_public_address');
        $this->assertBlocked(function () use ($policy): void {
            $policy->validate($this->request('http://evil.com/'));
        }, 'non_public_address');
    }

    public function testOneAllowedAddressDoesNotExemptAnother(): void
    {
        $policy = UrlPolicy::default()
            ->withAllowedHosts('8.8.8.8')
            ->withResolver(static function (string $host): array {
                return ['8.8.8.8', '10.0.0.1'];
            });

        $this->assertBlocked(function () use ($policy): void {
            $policy->validate($this->request('http://evil.example/'));
        }, 'non_public_address');

        $only = UrlPolicy::default()
            ->withAllowedHosts('10.0.0.1')
            ->withResolver(static function (string $host): array {
                return ['10.0.0.1'];
            });

        $this->assertSame(['10.0.0.1'], $only->validate($this->request('http://evil.example/')));
    }

    public function testAllowedHostMatchesTheUnicodeNameAndItsPunycode(): void
    {
        if (!function_exists('idn_to_ascii')) {
            $this->markTestSkipped('The intl extension is required to resolve IDN hosts.');
        }

        $policy = UrlPolicy::default()
            ->withAllowedHosts('Bücher.Example')
            ->withResolver(static function (string $host): array {
                return ['10.0.0.8'];
            });

        $this->assertSame(['10.0.0.8'], $policy->validate($this->request('http://bücher.example/')));

        $punycode = UrlPolicy::default()
            ->withAllowedHosts('xn--bcher-kva.example')
            ->withResolver(static function (string $host): array {
                return ['10.0.0.8'];
            });

        $this->assertSame(['10.0.0.8'], $punycode->validate($this->request('http://bücher.example/')));
        $this->assertSame('xn--bcher-kva.example.', $policy->curlResolveHost('bücher.example.'));
    }

    public function testAllowedPortsUseTheSchemeDefault(): void
    {
        $policy = $this->policy(['1.1.1.1'])->withAllowedPorts([80, 443]);

        $this->assertSame(['1.1.1.1'], $policy->validate($this->request('http://example.com/')));
        $this->assertSame(['1.1.1.1'], $policy->validate($this->request('https://example.com/')));
        $this->assertBlocked(function () use ($policy): void {
            $policy->validate($this->request('http://example.com:8080/'));
        }, 'port');
        $this->assertSame(['1.1.1.1'], $policy->validate($this->request('http://example.com:443/')));
    }

    public function testLiteralLoopbackSpellingsAreBlockedWithoutDns(): void
    {
        $policy = UrlPolicy::default()->withResolver(static function (string $host): array {
            throw new \RuntimeException('DNS was used for '.$host);
        });

        foreach ([
            'http://127.0.0.1/',
            'http://2130706433/',
            'http://127.1/',
            'http://0177.0.0.1/',
            'http://0x7f000001/',
            'http://0x7f.0.0.1/',
            'http://[::1]/',
            'http://[::ffff:127.0.0.1]/',
            'http://[::ffff:7f00:1]/',
            'http://[64:ff9b::7f00:1]/',
            'http://[64:ff9b::127.0.0.1]/',
            'http://[2002:7f00:1::]/',
            'http://169.254.169.254/',
        ] as $url) {
            $this->assertBlocked(function () use ($policy, $url): void {
                $policy->validate($this->request($url));
            }, 'non_public_address');
        }
    }

    public function testPublicNumericLiteralDoesNotUseDns(): void
    {
        $policy = UrlPolicy::default()->withResolver(static function (string $host): array {
            throw new \RuntimeException('DNS was used for '.$host);
        });

        $this->assertSame(['8.8.8.8'], $policy->validate($this->request('http://134744072/path')));
        $this->assertSame(['::ffff:8.8.8.8'], $policy->validate($this->request('http://[::ffff:8.8.8.8]/')));
    }

    /**
     * @return array<string, array{0: string, 1: string}>
     */
    public function rejectedUrlsProvider(): array
    {
        return [
            'ftp' => ['ftp://example.com/', 'scheme'],
            'file' => ['file:///etc/passwd', 'scheme'],
            'gopher' => ['gopher://127.0.0.1/', 'scheme'],
            'relative' => ['/x', 'scheme'],
            'protocol relative' => ['//example.com/x', 'scheme'],
            'userinfo' => ['http://user:pass@example.com/', 'host'],
            'backslash' => ['http://example.com\\@127.0.0.1/', 'host'],
            'space in host' => ['http://ex ample.com/', 'host'],
            'empty host' => ['http://', 'host'],
        ];
    }

    /**
     * @dataProvider rejectedUrlsProvider
     */
    public function testRejectedUrls(string $url, string $reason): void
    {
        try {
            $this->request($url);
        } catch (InvalidArgumentException $exception) {
            $this->assertContains($reason, ['scheme', 'host']);

            return;
        }

        $this->assertBlocked(function () use ($url): void {
            $this->policy(['8.8.8.8'])->validate($this->request($url));
        }, $reason);
    }

    public function testBlockedExceptionCarriesTheRequest(): void
    {
        $request = $this->request('http://127.0.0.1/secret');

        try {
            UrlPolicy::default()->validate($request);
            $this->fail('A loopback URL must be blocked.');
        } catch (BlockedRequestException $exception) {
            $this->assertSame($request, $exception->getRequest());
            $this->assertSame('non_public_address', $exception->getReason());
            $this->assertInstanceOf(InvalidArgumentException::class, $exception);
            $this->assertInstanceOf(RequestExceptionInterface::class, $exception);
        }
    }

    public function testWithersDoNotMutateTheOriginalPolicy(): void
    {
        $policy = UrlPolicy::default();
        $relaxed = $policy->allowPrivateNetworks();

        $this->assertNotSame($policy, $relaxed);
        $this->assertBlocked(function () use ($policy): void {
            $policy->validate($this->request('http://10.0.0.1/'));
        }, 'non_public_address');
        $this->assertSame(['10.0.0.1'], $relaxed->validate($this->request('http://10.0.0.1/')));
    }

    /**
     * @param array<int, string> $ips
     */
    private function policy(array $ips): UrlPolicy
    {
        return UrlPolicy::default()->withResolver(static function (string $host) use ($ips): array {
            return $ips;
        });
    }

    private function request(string $uri): RequestInterface
    {
        return (new Psr17Factory())->createRequest('GET', $uri);
    }

    /**
     * @param callable(): void $check
     */
    private function assertBlocked(callable $check, string $reason): BlockedRequestException
    {
        try {
            $check();
        } catch (BlockedRequestException $exception) {
            $this->assertSame($reason, $exception->getReason());
            $this->assertInstanceOf(InvalidArgumentException::class, $exception);
            $this->assertInstanceOf(RequestExceptionInterface::class, $exception);

            return $exception;
        }

        $this->fail('Expected BlockedRequestException with reason '.$reason);
    }
}
