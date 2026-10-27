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
 * oEmbed responses of type "photo" carry no "html", only "url".
 */
class OEmbedPhotoCodeTest extends TestCase
{
    public function testPhotoWithoutHtmlBuildsAnImage(): void
    {
        $info = $this->embed([
            'type' => 'photo',
            'version' => '1.0',
            'title' => 'Funny "cat" GIF',
            'url' => 'https://media.public.test/cat.gif',
            'width' => 480,
            'height' => 270,
        ])->get('http://public.test/gifs/cat');

        $code = $info->code;
        $this->assertNotNull($code);
        $this->assertSame(
            '<img src="https://media.public.test/cat.gif" alt="Funny &quot;cat&quot; GIF" width="480" height="270" />',
            $code->html
        );
        $this->assertSame(480, $code->width);
        $this->assertSame(270, $code->height);
    }

    public function testPhotoWithoutSizeOmitsDimensions(): void
    {
        $info = $this->embed([
            'type' => 'photo',
            'version' => '1.0',
            'url' => 'https://media.public.test/cat.gif',
        ])->get('http://public.test/gifs/cat');

        $code = $info->code;
        $this->assertNotNull($code);
        $this->assertSame('<img src="https://media.public.test/cat.gif" />', $code->html);
        $this->assertNull($code->width);
        $this->assertNull($code->height);
    }

    public function testHtmlStillWinsOverPhotoUrl(): void
    {
        $info = $this->embed([
            'type' => 'photo',
            'version' => '1.0',
            'url' => 'https://media.public.test/cat.gif',
            'html' => '<blockquote>cat</blockquote>',
        ])->get('http://public.test/gifs/cat');

        $code = $info->code;
        $this->assertNotNull($code);
        $this->assertSame('<blockquote>cat</blockquote>', $code->html);
    }

    public function testNonPhotoWithoutHtmlHasNoCode(): void
    {
        $info = $this->embed([
            'type' => 'link',
            'version' => '1.0',
            'url' => 'https://media.public.test/cat.gif',
        ])->get('http://public.test/gifs/cat');

        $this->assertNull($info->code);
    }

    /**
     * @param array<string, mixed> $oembed
     */
    private function embed(array $oembed): Embed
    {
        $json = (string) json_encode($oembed, JSON_UNESCAPED_SLASHES);
        $page = '<!DOCTYPE html><html><head><title>Page title</title>'
            .'<link rel="alternate" type="application/json+oembed" href="http://public.test/oembed?url=cat">'
            .'</head><body></body></html>';

        $client = new RecordingClient(static function (RequestInterface $request) use ($json, $page): ResponseInterface {
            $factory = new Psr17Factory();
            $isOEmbed = $request->getUri()->getPath() === '/oembed';

            return $factory->createResponse(200)
                ->withHeader('Content-Type', $isOEmbed ? 'application/json' : 'text/html')
                ->withBody($factory->createStream($isOEmbed ? $json : $page));
        });

        $crawler = new Crawler($client);
        $crawler->setUrlPolicy(UrlPolicy::default()->withResolver(static function (string $host): array {
            return ['1.1.1.1'];
        }));

        return new Embed($crawler);
    }
}
