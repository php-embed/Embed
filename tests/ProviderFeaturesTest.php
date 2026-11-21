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
 * Provider features added in 4.7.0, tested with offline pages.
 */
class ProviderFeaturesTest extends TestCase
{
    private const YOUTUBE_ID = 'dQw4w9WgXcQ';

    public function testYoutubeUsesMaxresdefaultFromThePage(): void
    {
        $id = self::YOUTUBE_ID;
        $info = $this->embed([
            'www.youtube.com/watch' => $this->page("<meta property=\"og:image\" content=\"https://i.ytimg.com/vi/{$id}/maxresdefault.jpg\">"),
            'www.youtube.com/oembed' => $this->youtubeOEmbed(),
        ])->get("https://www.youtube.com/watch?v={$id}");

        $this->assertSame("https://i.ytimg.com/vi/{$id}/maxresdefault.jpg", (string) $info->image);
    }

    public function testYoutubeKeepsHqdefaultWithoutMaxres(): void
    {
        $id = self::YOUTUBE_ID;
        $info = $this->embed([
            'www.youtube.com/watch' => $this->page("<meta property=\"og:image\" content=\"https://i.ytimg.com/vi/{$id}/hqdefault.jpg\">"),
            'www.youtube.com/oembed' => $this->youtubeOEmbed(),
        ])->get("https://www.youtube.com/watch?v={$id}");

        $this->assertSame("https://i.ytimg.com/vi/{$id}/hqdefault.jpg", (string) $info->image);
    }

    public function testYoutubeIgnoresMaxresdefaultOfAnotherVideo(): void
    {
        $id = self::YOUTUBE_ID;
        $info = $this->embed([
            'www.youtube.com/watch' => $this->page('<meta property="og:image" content="https://i.ytimg.com/vi/otherVideo1/maxresdefault.jpg">'),
            'www.youtube.com/oembed' => $this->youtubeOEmbed(),
        ])->get("https://www.youtube.com/watch?v={$id}");

        $this->assertSame("https://i.ytimg.com/vi/{$id}/hqdefault.jpg", (string) $info->image);
    }

    public function testYoutubeAddsTheEndTimeToThePlayer(): void
    {
        $id = self::YOUTUBE_ID;
        $info = $this->embed([
            'www.youtube.com/watch' => $this->page(''),
            'www.youtube.com/oembed' => $this->youtubeOEmbed("https://www.youtube.com/embed/{$id}?start=10&feature=oembed"),
        ])->get("https://www.youtube.com/watch?v={$id}&start=10&end=20");

        $this->assertNotNull($info->code);
        $this->assertStringContainsString("src=\"https://www.youtube.com/embed/{$id}?start=10&feature=oembed&end=20\"", $info->code->html);
        $this->assertSame(200, $info->code->width);
        $this->assertSame(113, $info->code->height);
    }

    public function testYoutubeAcceptsTheEndTimeInMinutesAndSeconds(): void
    {
        $id = self::YOUTUBE_ID;
        $info = $this->embed([
            'www.youtube.com/watch' => $this->page(''),
            'www.youtube.com/oembed' => $this->youtubeOEmbed("https://www.youtube.com/embed/{$id}"),
        ])->get("https://www.youtube.com/watch?v={$id}&end=1m30s");

        $this->assertNotNull($info->code);
        $this->assertStringContainsString("src=\"https://www.youtube.com/embed/{$id}?end=90\"", $info->code->html);
    }

    public function testYoutubeAddsTheStartTimeWhenOEmbedDropsIt(): void
    {
        $id = self::YOUTUBE_ID;
        $info = $this->embed([
            'www.youtube.com/watch' => $this->page(''),
            'www.youtube.com/oembed' => $this->youtubeOEmbed(),
        ])->get("https://www.youtube.com/watch?v={$id}&t=1m5s&end=90");

        $this->assertNotNull($info->code);
        $this->assertStringContainsString("src=\"https://www.youtube.com/embed/{$id}?feature=oembed&start=65&end=90\"", $info->code->html);
    }

    public function testYoutubeWithoutEndTimeKeepsTheOEmbedCode(): void
    {
        $id = self::YOUTUBE_ID;
        $info = $this->embed([
            'www.youtube.com/watch' => $this->page(''),
            'www.youtube.com/oembed' => $this->youtubeOEmbed(),
        ])->get("https://www.youtube.com/watch?v={$id}&end=soon");

        $this->assertNotNull($info->code);
        $this->assertStringContainsString("src=\"https://www.youtube.com/embed/{$id}?feature=oembed\"", $info->code->html);
    }

    /**
     * @dataProvider vimeoThumbnailProvider
     */
    public function testVimeoUsesTheFullSizeThumbnail(string $thumbnail, string $expected): void
    {
        $info = $this->embed([
            'vimeo.com/76979871' => $this->page(''),
            'vimeo.com/api/oembed.json' => ['application/json', (string) json_encode([
                'type' => 'video',
                'title' => 'Video',
                'thumbnail_url' => $thumbnail,
                'thumbnail_width' => 295,
                'thumbnail_height' => 166,
            ])],
        ])->get('https://vimeo.com/76979871');

        $this->assertSame($expected, (string) $info->image);
    }

    public function vimeoThumbnailProvider(): array
    {
        return [
            'current' => [
                'https://i.vimeocdn.com/video/452001751-8216e0571c251a09d7a8387550942d89f7f86f6398f8ed886e639b0dd50d3c90-d_295x166?region=us',
                'https://i.vimeocdn.com/video/452001751-8216e0571c251a09d7a8387550942d89f7f86f6398f8ed886e639b0dd50d3c90-d?region=us',
            ],
            'width only' => [
                'https://i.vimeocdn.com/video/452001751-8216e0571c251a09d7a8387550942d89f7f86f6398f8ed886e639b0dd50d3c90-d_640',
                'https://i.vimeocdn.com/video/452001751-8216e0571c251a09d7a8387550942d89f7f86f6398f8ed886e639b0dd50d3c90-d',
            ],
            'legacy' => [
                'https://i.vimeocdn.com/video/938592587_295x166.jpg',
                'https://i.vimeocdn.com/video/938592587.jpg',
            ],
            'full size' => [
                'https://i.vimeocdn.com/video/938592587.jpg',
                'https://i.vimeocdn.com/video/938592587.jpg',
            ],
            'other host' => [
                'https://example.com/video/938592587_295x166.jpg',
                'https://example.com/video/938592587_295x166.jpg',
            ],
        ];
    }

    /**
     * @param array<string, string|array{0: string, 1: string}> $routes host+path => html, or [content type, body]
     * @param array<string, mixed> $settings
     */
    private function embed(array $routes, array $settings = []): Embed
    {
        $client = new RecordingClient(static function (RequestInterface $request) use ($routes): ResponseInterface {
            $factory = new Psr17Factory();
            $uri = $request->getUri();
            $route = $routes[$uri->getHost().$uri->getPath()] ?? null;

            if ($route === null) {
                return $factory->createResponse(404)->withBody($factory->createStream(''));
            }

            [$type, $body] = is_array($route) ? $route : ['text/html', $route];

            return $factory->createResponse(200)
                ->withHeader('Content-Type', $type)
                ->withBody($factory->createStream($body));
        });

        $crawler = new Crawler($client);
        $crawler->setUrlPolicy(UrlPolicy::default()->withResolver(static function (string $host): array {
            return ['1.1.1.1'];
        }));

        $embed = new Embed($crawler);
        $embed->setSettings($settings);

        return $embed;
    }

    private function page(string $head): string
    {
        return "<!DOCTYPE html><html><head><title>Test page</title>{$head}</head><body></body></html>";
    }

    /**
     * @return array{0: string, 1: string}
     */
    private function youtubeOEmbed(string $src = 'https://www.youtube.com/embed/dQw4w9WgXcQ?feature=oembed'): array
    {
        return ['application/json', (string) json_encode([
            'type' => 'video',
            'title' => 'Video',
            'provider_name' => 'YouTube',
            'thumbnail_url' => 'https://i.ytimg.com/vi/'.self::YOUTUBE_ID.'/hqdefault.jpg',
            'width' => 200,
            'height' => 113,
            'html' => '<iframe width="200" height="113" src="'.$src.'" frameborder="0" allowfullscreen></iframe>',
        ])];
    }
}
