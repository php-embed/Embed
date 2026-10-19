<?php
declare(strict_types = 1);

namespace Embed\Tests;

use Embed\Http\IpClassifier;
use PHPUnit\Framework\TestCase;

class IpClassifierTest extends TestCase
{
    /**
     * @return array<string, array{0: string, 1: bool}>
     */
    public function addressesProvider(): array
    {
        return [
            'unspecified v4' => ['0.0.0.0', false],
            'zero network end' => ['0.255.255.255', false],
            'private 10 start' => ['10.0.0.0', false],
            'private 10 end' => ['10.255.255.255', false],
            'just outside 10' => ['11.0.0.1', true],
            'cgnat start' => ['100.64.0.0', false],
            'cgnat end' => ['100.127.255.255', false],
            'below cgnat' => ['100.63.255.255', true],
            'above cgnat' => ['100.128.0.0', true],
            'loopback' => ['127.0.0.1', false],
            'loopback end' => ['127.255.255.255', false],
            'link local start' => ['169.254.0.0', false],
            'metadata' => ['169.254.169.254', false],
            'below private 172' => ['172.15.255.255', true],
            'private 172 start' => ['172.16.0.0', false],
            'private 172 end' => ['172.31.255.255', false],
            'above private 172' => ['172.32.0.0', true],
            'ietf protocol' => ['192.0.0.0', false],
            'ietf protocol end' => ['192.0.0.255', false],
            'outside ietf protocol' => ['192.0.1.1', true],
            'test net 1' => ['192.0.2.1', false],
            '6to4 relay anycast' => ['192.88.99.1', false],
            'rfc1918 192.168' => ['192.168.1.1', false],
            'benchmark start' => ['198.18.0.1', false],
            'benchmark end' => ['198.19.255.255', false],
            'below benchmark' => ['198.17.255.255', true],
            'above benchmark' => ['198.20.0.1', true],
            'test net 2' => ['198.51.100.1', false],
            'test net 3' => ['203.0.113.1', false],
            'below multicast' => ['223.255.255.255', true],
            'multicast start' => ['224.0.0.1', false],
            'multicast end' => ['239.255.255.255', false],
            'reserved start' => ['240.0.0.1', false],
            'broadcast' => ['255.255.255.255', false],
            'google dns' => ['8.8.8.8', true],
            'cloudflare dns' => ['1.1.1.1', true],
            'unspecified v6' => ['::', false],
            'loopback v6' => ['::1', false],
            'unique local fc' => ['fc00::1', false],
            'unique local fd' => ['fd12:3456:789a::1', false],
            'link local v6' => ['fe80::1', false],
            'link local v6 end' => ['febf::1', false],
            'site local v6' => ['fec0::1', false],
            'multicast v6' => ['ff02::1', false],
            'documentation v6' => ['2001:db8::1', false],
            'discard prefix' => ['100::', false],
            'discard prefix host' => ['100::1', false],
            'teredo' => ['2001:0:4136:e378::1', false],
            'nat64 local use' => ['64:ff9b:1::1', false],
            'google dns v6' => ['2001:4860:4860::8888', true],
            'cloudflare dns v6' => ['2606:4700:4700::1111', true],
            'outside discard prefix' => ['100:0:0:1::', true],
            'mapped public' => ['::ffff:8.8.8.8', true],
            'mapped public hex' => ['::ffff:808:808', true],
            'mapped loopback' => ['::ffff:127.0.0.1', false],
            'mapped loopback hex' => ['::ffff:7f00:1', false],
            'compatible public' => ['::8.8.8.8', true],
            'compatible loopback' => ['::7f00:1', false],
            'nat64 public' => ['64:ff9b::8.8.8.8', true],
            'nat64 loopback' => ['64:ff9b::127.0.0.1', false],
            'nat64 loopback hex' => ['64:ff9b::7f00:1', false],
            '6to4 public' => ['2002:808:808::', true],
            '6to4 loopback' => ['2002:7f00:1::', false],
            '6to4 rfc1918' => ['2002:c0a8:1::', false],
            'decimal loopback' => ['2130706433', false],
            'short loopback' => ['127.1', false],
            'octal loopback' => ['0177.0.0.1', false],
            'hex loopback' => ['0x7f000001', false],
            'dotted hex loopback' => ['0x7f.0.0.1', false],
            'decimal public' => ['134744072', true],
            'not an ip' => ['example.com', false],
            'empty' => ['', false],
            'five octets' => ['8.8.8.8.8', false],
        ];
    }

    /**
     * @dataProvider addressesProvider
     */
    public function testIsPublic(string $ip, bool $public): void
    {
        $this->assertSame($public, IpClassifier::isPublic($ip));
    }

    public function testCanonicalNormalizesAlternativeSpellings(): void
    {
        $this->assertSame('127.0.0.1', IpClassifier::canonical('2130706433'));
        $this->assertSame('127.0.0.1', IpClassifier::canonical('127.1'));
        $this->assertSame('127.0.0.1', IpClassifier::canonical('0177.0.0.1'));
        $this->assertSame('127.0.0.1', IpClassifier::canonical('0x7f000001'));
        $this->assertSame('8.8.8.8', IpClassifier::canonical('134744072'));
        $this->assertSame('8.8.8.8', IpClassifier::canonical('8.8.8.8'));
        $this->assertNull(IpClassifier::canonical('example.com'));
        $this->assertNull(IpClassifier::canonical('0x7f.example.com'));
    }

    public function testEqualsTreatsIpv4MappedAsTheSameAddress(): void
    {
        $this->assertTrue(IpClassifier::equals('127.0.0.1', '::ffff:127.0.0.1'));
        $this->assertTrue(IpClassifier::equals('8.8.8.8', '::ffff:808:808'));
        $this->assertFalse(IpClassifier::equals('8.8.8.8', '1.1.1.1'));
        $this->assertFalse(IpClassifier::equals('8.8.8.8', '2002:808:808::'));
    }
}
