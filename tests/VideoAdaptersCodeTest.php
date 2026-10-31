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
 * Embed code for Kick, Twitch, and HLS og:video pages.
 */
class VideoAdaptersCodeTest extends TestCase
{
    public function testHlsOpenGraphVideoHasNoIframe(): void
    {
        $info = $this->embed([
            '/' => $this->page('<meta property="og:video" content="https://media.public.test/clip/playlist.m3u8">'),
        ])->get('http://public.test/');

        $this->assertNull($info->code);
    }

    public function testHlsOpenGraphVideoTypeHasNoIframe(): void
    {
        $info = $this->embed([
            '/' => $this->page(
                '<meta property="og:video" content="https://media.public.test/clip/stream">'
                .'<meta property="og:video:type" content="application/x-mpegURL">'
            ),
        ])->get('http://public.test/');

        $this->assertNull($info->code);
    }

    public function testOtherOpenGraphVideoStillBuildsIframe(): void
    {
        $info = $this->embed([
            '/' => $this->page('<meta property="og:video" content="https://player.public.test/embed/1">'),
        ])->get('http://public.test/');

        $this->assertNotNull($info->code);
        $this->assertStringContainsString('<iframe src="https://player.public.test/embed/1"', $info->code->html);
    }

    public function testKickChannelBuildsPlayerIframe(): void
    {
        $info = $this->embed(['/xqc' => $this->page('')])->get('https://kick.com/xqc');

        $this->assertNotNull($info->code);
        $this->assertSame(
            '<iframe src="https://player.kick.com/xqc" frameborder="0" allowfullscreen="true" scrolling="no" width="640" height="360"></iframe>',
            $info->code->html
        );
        $this->assertSame(640, $info->code->width);
        $this->assertSame(360, $info->code->height);
    }

    public function testKickClipHasNoHlsIframe(): void
    {
        $info = $this->embed([
            '/xqc/clips/clip_01ABC' => $this->page('<meta property="og:video" content="https://clips.kick.com/clips/8d/clip_01ABC/playlist.m3u8">'),
        ])->get('https://kick.com/xqc/clips/clip_01ABC');

        $this->assertNull($info->code);
    }

    public function testKickVideoReadsTheApi(): void
    {
        $uuid = '4aeefefc-cd67-413e-8107-14327b21f5a3';
        $info = $this->embed([
            "/xqc/videos/{$uuid}" => $this->page(''),
            "/api/v1/video/{$uuid}" => (string) json_encode([
                'uuid' => $uuid,
                'livestream' => [
                    'session_title' => 'Stream title',
                    'thumbnail' => null,
                    'channel' => ['slug' => 'xqc', 'user' => ['username' => 'xQc']],
                ],
            ]),
            '/api/v2/channels/xqc/videos' => (string) json_encode([
                ['video' => ['uuid' => 'other'], 'thumbnail' => ['src' => 'https://images.kick.com/other.webp']],
                ['video' => ['uuid' => $uuid], 'thumbnail' => ['src' => 'https://images.kick.com/720.webp']],
            ]),
        ])->get("https://kick.com/xqc/videos/{$uuid}");

        $this->assertSame('Stream title', $info->title);
        $this->assertSame('xQc', $info->authorName);
        $this->assertSame('https://images.kick.com/720.webp', (string) $info->image);
        $this->assertNull($info->code);
    }

    public function testTwitchVideoIframeUsesVideoParameter(): void
    {
        $info = $this->embed(['/videos/72749628' => $this->page('')], ['twitch:parent' => 'example.com'])
            ->get('https://www.twitch.tv/videos/72749628');

        $this->assertNotNull($info->code);
        $this->assertStringContainsString(
            'src="https://player.twitch.tv/?autoplay=false&amp;video=v72749628&amp;parent=example.com"',
            $info->code->html
        );
    }

    public function testTwitchClipIframeWithParent(): void
    {
        $settings = ['twitch:parent' => 'example.com'];
        $expected = 'src="https://clips.twitch.tv/embed?clip=IncredulousAbstemiousFennelImGlitch&amp;parent=example.com&amp;autoplay=false"';

        $info = $this->embed(['/IncredulousAbstemiousFennelImGlitch' => $this->page('')], $settings)
            ->get('https://clips.twitch.tv/IncredulousAbstemiousFennelImGlitch');
        $this->assertNotNull($info->code);
        $this->assertStringContainsString($expected, $info->code->html);

        $info = $this->embed(['/twitchdev/clip/IncredulousAbstemiousFennelImGlitch' => $this->page('')], $settings)
            ->get('https://www.twitch.tv/twitchdev/clip/IncredulousAbstemiousFennelImGlitch');
        $this->assertNotNull($info->code);
        $this->assertStringContainsString($expected, $info->code->html);
    }

    public function testTwitchClipWithoutParentHasNoCode(): void
    {
        $info = $this->embed(['/twitchdev/clip/IncredulousAbstemiousFennelImGlitch' => $this->page('')])
            ->get('https://www.twitch.tv/twitchdev/clip/IncredulousAbstemiousFennelImGlitch');

        $this->assertNull($info->code);
    }

    private function page(string $head): string
    {
        return '<!DOCTYPE html><html><head><title>Page title</title>'.$head.'</head><body></body></html>';
    }

    /**
     * @param array<string, string> $routes path => body
     * @param array<string, mixed> $settings
     */
    private function embed(array $routes, array $settings = []): Embed
    {
        $client = new RecordingClient(static function (RequestInterface $request) use ($routes): ResponseInterface {
            $factory = new Psr17Factory();
            $path = $request->getUri()->getPath();
            $body = $routes[$path] ?? null;

            if ($body === null) {
                return $factory->createResponse(404)->withBody($factory->createStream(''));
            }

            $isJson = strpos($path, '/api/') === 0;

            return $factory->createResponse(200)
                ->withHeader('Content-Type', $isJson ? 'application/json' : 'text/html')
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
}
