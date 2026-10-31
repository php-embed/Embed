<?php
declare(strict_types = 1);

namespace Embed\Tests;

use function Embed\clean;
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

    /**
     * @see https://github.com/php-embed/Embed/issues/372
     */
    public function testCleanKeepsALessThanSignThatIsNotATag(): void
    {
        $this->assertSame('Brytere =<250A | Shop', clean('Brytere =&lt;250A | Shop'));
        $this->assertSame('1 < 2 and 3 <= 4', clean('1 < 2 and 3 &lt;= 4'));
        $this->assertSame('Bold text', clean('<b>Bold</b> <img src="x"/>text<!-- c -->'));
        $this->assertSame('alert(1)Hi', clean('&lt;script&gt;alert(1)&lt;/script&gt;Hi'));
    }
}
