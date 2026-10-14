<?php
declare(strict_types = 1);

namespace Embed\Tests;

use function Embed\isHttp;
use function Embed\resolveUri;
use Nyholm\Psr7\Factory\Psr17Factory;
use PHPUnit\Framework\TestCase;

class FunctionsTest extends TestCase
{
    public function urlsProvider(): array
    {
        return [
            ['https://foo.com', true],
            ['http://foo.com', true],
            ['mailto:foo@example.com', false],
            ['tel:+1234567890', false],
            ['data:foo', false],
            ['./foo', true],
            ['/foo', true],
            ['../foo', true],
            ['foo.com', true],
            ['//foo.com', true],
        ];
    }

    /**
     * @dataProvider urlsProvider
     */
    public function testIsHttp(string $url, bool $expected)
    {
        $result = isHttp($url);
        $this->assertSame($expected, $result);
    }

    public function testResolveUriKeepsTheBasePort(): void
    {
        $factory = new Psr17Factory();
        $base = $factory->createUri('http://h:8080/a/b');

        $absolute = resolveUri($base, $factory->createUri('/x'));
        $this->assertSame('http://h:8080/x', (string) $absolute);

        $relative = resolveUri($base, $factory->createUri('c'));
        $this->assertSame('http://h:8080/a/c', (string) $relative);

        $protocolRelative = resolveUri($base, $factory->createUri('//cdn.example/favicon.ico'));
        $this->assertSame('http://cdn.example/favicon.ico', (string) $protocolRelative);

        $other = resolveUri($base, $factory->createUri('https://other.example:9/z'));
        $this->assertSame('https://other.example:9/z', (string) $other);
    }
}
