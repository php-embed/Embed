<?php
declare(strict_types = 1);

namespace Embed\Tests;

use Embed\Embed;
use Embed\Http\Crawler;
use Embed\Http\UrlPolicy;
use Nyholm\Psr7\Factory\Psr17Factory;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * Regressions fixed in 4.5.5, with fake responses and no network.
 */
class PatchFixesTest extends TestCase
{
    /**
     * @see https://github.com/php-embed/Embed/issues/372
     */
    public function testLessThanSignInTheTitleIsKept(): void
    {
        $info = $this->embed(static fn (): array => [
            'text/html; charset=utf-8',
            '<html><head><title>Tilbeh&oslash;r for brytere =&lt;250A | Effektbrytere</title></head><body></body></html>',
            null,
        ])->get('https://shop.public.test/category');

        $this->assertSame('Tilbehør for brytere =<250A | Effektbrytere', $info->title);
    }

    /**
     * @see https://github.com/php-embed/Embed/issues/485
     */
    public function testSpacesInsideAnImageUrlAreKept(): void
    {
        $info = $this->embed(static fn (): array => [
            'text/html; charset=utf-8',
            '<html><head><meta property="og:image" content="https://www.public.test/user/pages/01.home/1024-576  logo.jpg"></head><body></body></html>',
            null,
        ])->get('https://www.public.test/');

        $this->assertSame('https://www.public.test/user/pages/01.home/1024-576%20%20logo.jpg', (string) $info->image);
    }

    /**
     * @see https://github.com/php-embed/Embed/issues/524
     */
    public function testImageBehindARedirectIsEmbeddedFromTheFinalUrl(): void
    {
        $info = $this->embed(static fn (): array => [
            'image/jpeg',
            '',
            'https://cdn.public.test/hug.jpeg',
        ])->get('https://cdn.public.test/301/hug.jpeg');

        $this->assertSame('https://cdn.public.test/hug.jpeg', (string) $info->url);
        $this->assertNotNull($info->code);
        $this->assertSame('<img src="https://cdn.public.test/hug.jpeg" />', $info->code->html);
    }

    /**
     * @return array<string, array{0: string, 1: string}>
     */
    public function twitterRedirectProvider(): array
    {
        return [
            'x.com to /home' => ['https://x.com/pepephone/status/436461658601713664', 'https://x.com/home'],
            'twitter.com to the migrate form' => ['https://twitter.com/pepephone/status/436461658601713664', 'https://x.com/x/migrate'],
            'mobile twitter.com to a login wall' => ['https://mobile.twitter.com/pepephone/status/436461658601713664?s=20', 'https://x.com/i/flow/login'],
        ];
    }

    /**
     * @dataProvider twitterRedirectProvider
     * @see https://github.com/php-embed/Embed/issues/520
     * @see https://github.com/php-embed/Embed/issues/536
     */
    public function testTwitterUsesTheRequestedStatusForOEmbed(string $url, string $final): void
    {
        $oembed = (string) json_encode([
            'url' => 'https://twitter.com/pepephone/status/436461658601713664',
            'author_name' => 'Pepephone',
            'author_url' => 'https://twitter.com/pepephone',
            'html' => '<blockquote class="twitter-tweet"><p>Pepephone rompe la baraja</p></blockquote>',
            'width' => 550,
            'type' => 'rich',
            'version' => '1.0',
        ], JSON_UNESCAPED_SLASHES);

        $client = null;
        $embed = $this->embed(static function (RequestInterface $request) use ($oembed, $final): array {
            if ($request->getUri()->getHost() === 'publish.twitter.com') {
                return ['application/json', $oembed, null];
            }

            return ['text/html; charset=utf-8', '<html><head><title>X</title></head><body>Log in</body></html>', $final];
        }, $client);

        $info = $embed->get($url);

        $this->assertNotNull($client);
        $oembedRequests = array_values(array_filter(
            $client->requests,
            static fn (RequestInterface $request): bool => $request->getUri()->getHost() === 'publish.twitter.com'
        ));
        $this->assertCount(1, $oembedRequests);
        parse_str($oembedRequests[0]->getUri()->getQuery(), $query);
        $this->assertStringEndsWith('/pepephone/status/436461658601713664', (string) $query['url']);
        $this->assertStringNotContainsString('home', (string) $query['url']);

        $this->assertNotNull($info->code);
        $this->assertStringContainsString('twitter-tweet', $info->code->html);
        $this->assertSame('Pepephone', $info->authorName);
        $this->assertSame('Tweet by Pepephone', $info->title);
        $this->assertSame('https://twitter.com/pepephone/status/436461658601713664', (string) $info->url);
    }

    /**
     * @param callable(RequestInterface): array{0: string, 1: string, 2: ?string} $handler
     * @param-out RecordingClient $client
     */
    private function embed(callable $handler, ?RecordingClient &$client = null): Embed
    {
        $client = new RecordingClient(static function (RequestInterface $request) use ($handler): ResponseInterface {
            [$type, $body, $final] = $handler($request);
            $factory = new Psr17Factory();

            return $factory->createResponse(200)
                ->withHeader('Content-Type', $type)
                ->withHeader('Content-Location', $final ?? (string) $request->getUri())
                ->withBody($factory->createStream($body));
        });

        $crawler = new Crawler($client);
        $crawler->setUrlPolicy(UrlPolicy::default()->withResolver(static function (string $host): array {
            return ['1.1.1.1'];
        }));

        return new Embed($crawler);
    }
}
