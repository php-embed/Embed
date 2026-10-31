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
 * The "mastodon:iframe" setting turns Mastodon's oEmbed blockquote into an iframe.
 */
class MastodonIframeTest extends TestCase
{
    private const SANDBOX = 'allow-scripts allow-same-origin allow-popups allow-popups-to-escape-sandbox';

    /**
     * @return array<string, array{string, string}>
     */
    public static function recordedPosts(): array
    {
        return [
            'fosstodon' => [
                'https://fosstodon.org/@AkaSci/117514634840174369',
                'https://fosstodon.org/@AkaSci/117514634840174369/embed',
            ],
            'hachyderm' => [
                'https://hachyderm.io/@briancmoses/117514657412674435',
                'https://hachyderm.io/@briancmoses/117514657412674435/embed',
            ],
        ];
    }

    /**
     * @dataProvider recordedPosts
     */
    public function testRecordedPostWithSettingIsAnIframe(string $url, string $embedUrl): void
    {
        $code = $this->recorded(['mastodon:iframe' => true])->get($url)->code;

        $this->assertNotNull($code);
        $this->assertSame(
            '<iframe src="'.$embedUrl.'" class="mastodon-embed" style="max-width: 100%; border: 0" width="400" sandbox="'.self::SANDBOX.'" allowfullscreen="allowfullscreen" loading="lazy"></iframe>',
            $code->html
        );
        $this->assertSame(400, $code->width);
        $this->assertNull($code->height);
    }

    /**
     * @dataProvider recordedPosts
     */
    public function testRecordedPostWithoutSettingKeepsTheBlockquote(string $url, string $embedUrl): void
    {
        $code = $this->recorded([])->get($url)->code;

        $this->assertNotNull($code);
        $this->assertStringStartsWith('<blockquote class="mastodon-embed" data-embed-url="'.$embedUrl.'"', $code->html);
        $this->assertStringContainsString('embed.js', $code->html);
    }

    public function testEmbedUrlOnAnotherHostIsIgnored(): void
    {
        $code = $this->fake('<blockquote class="mastodon-embed" data-embed-url="https://evil.test/@a/1/embed">x</blockquote>')
            ->get('https://social.test/@a/1')->code;

        $this->assertNotNull($code);
        $this->assertStringStartsWith('<blockquote', $code->html);
    }

    public function testNonHttpsEmbedUrlIsIgnored(): void
    {
        $code = $this->fake('<blockquote class="mastodon-embed" data-embed-url="http://social.test/@a/1/embed">x</blockquote>')
            ->get('https://social.test/@a/1')->code;

        $this->assertNotNull($code);
        $this->assertStringStartsWith('<blockquote', $code->html);
    }

    public function testOtherOEmbedHtmlIsUntouched(): void
    {
        $code = $this->fake('<blockquote class="twitter-tweet">x</blockquote>')
            ->get('https://social.test/@a/1')->code;

        $this->assertNotNull($code);
        $this->assertSame('<blockquote class="twitter-tweet">x</blockquote>', $code->html);
    }

    /**
     * @param array<string, mixed> $settings
     */
    private function recorded(array $settings): Embed
    {
        $client = new FileClient(__DIR__.'/cache');
        $client->setMode(-1);
        $crawler = new Crawler($client);
        $crawler->setUrlPolicy(UrlPolicy::default()->withResolver(static function (string $host): array {
            return ['8.8.8.8'];
        }));

        $embed = new Embed($crawler);
        $embed->setSettings($settings);

        return $embed;
    }

    private function fake(string $html): Embed
    {
        $json = (string) json_encode(['type' => 'rich', 'version' => '1.0', 'html' => $html, 'width' => 400], JSON_UNESCAPED_SLASHES);
        $page = '<!DOCTYPE html><html><head><title>Post</title>'
            .'<link rel="alternate" type="application/json+oembed" href="https://social.test/api/oembed?url=post">'
            .'</head><body></body></html>';

        $client = new RecordingClient(static function (RequestInterface $request) use ($json, $page): ResponseInterface {
            $factory = new Psr17Factory();
            $isOEmbed = $request->getUri()->getPath() === '/api/oembed';

            return $factory->createResponse(200)
                ->withHeader('Content-Type', $isOEmbed ? 'application/json' : 'text/html')
                ->withBody($factory->createStream($isOEmbed ? $json : $page));
        });

        $crawler = new Crawler($client);
        $crawler->setUrlPolicy(UrlPolicy::default()->withResolver(static function (string $host): array {
            return ['1.1.1.1'];
        }));

        $embed = new Embed($crawler);
        $embed->setSettings(['mastodon:iframe' => true]);

        return $embed;
    }
}
