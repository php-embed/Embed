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
 * Vimeo videos with domain-level privacy only return their full oEmbed
 * data when the request carries a Referer from an allowed domain.
 */
class VimeoRefererTest extends TestCase
{
    private const ALLOWED = 'https://allowed.example/';

    public function testRefererFromTheCrawlerReachesTheOEmbedEndpoint(): void
    {
        $client = $this->client(200);
        $embed = $this->embed($client);
        $embed->getCrawler()->addDefaultHeaders(['Referer' => self::ALLOWED]);

        $info = $embed->get('https://vimeo.com/304219740');
        $oembed = $info->getOEmbed();

        $this->assertSame('Private video', $info->title);
        $this->assertSame(200, $oembed->int('domain_status_code'));
        $this->assertSame('https://i.vimeocdn.test/video/304219740.jpg', (string) $info->image);

        $endpoints = array_values(array_filter(
            $client->requests,
            static fn (RequestInterface $request): bool => $request->getUri()->getPath() === '/api/oembed.json'
        ));
        $this->assertCount(1, $endpoints);
        $this->assertSame(self::ALLOWED, $endpoints[0]->getHeaderLine('Referer'));
    }

    public function testWithoutRefererTheResponseIsSimplified(): void
    {
        $info = $this->embed($this->client(200))->get('https://vimeo.com/304219740');
        $oembed = $info->getOEmbed();

        $this->assertNotSame([], $oembed->all());
        $this->assertSame(403, $oembed->int('domain_status_code'));
        $this->assertNull($oembed->str('title'));
        $this->assertNotNull($info->code);
    }

    public function testFailedOEmbedRequestReturnsNoData(): void
    {
        $info = $this->embed($this->client(404))->get('https://vimeo.com/304219740');

        $this->assertSame([], $info->getOEmbed()->all());
    }

    private function client(int $oembedStatus): RecordingClient
    {
        return new RecordingClient(static function (RequestInterface $request) use ($oembedStatus): ResponseInterface {
            $factory = new Psr17Factory();

            if ($request->getUri()->getPath() !== '/api/oembed.json') {
                return $factory->createResponse(200)
                    ->withHeader('Content-Type', 'text/html')
                    ->withBody($factory->createStream('<!DOCTYPE html><html><head><title>Vimeo</title></head><body></body></html>'));
            }

            if ($oembedStatus !== 200) {
                return $factory->createResponse($oembedStatus)
                    ->withHeader('Content-Type', 'text/html')
                    ->withBody($factory->createStream('404 Not Found'));
            }

            $data = [
                'type' => 'video',
                'version' => '1.0',
                'provider_name' => 'Vimeo',
                'provider_url' => 'https://vimeo.com/',
                'html' => '<iframe src="https://player.vimeo.com/video/304219740" width="640" height="360"></iframe>',
                'width' => 640,
                'height' => 360,
                'video_id' => 304219740,
            ];

            if ($request->getHeaderLine('Referer') === self::ALLOWED) {
                $data += [
                    'title' => 'Private video',
                    'thumbnail_url' => 'https://i.vimeocdn.test/video/304219740.jpg',
                    'domain_status_code' => 200,
                ];
            } else {
                $data['domain_status_code'] = 403;
            }

            return $factory->createResponse(200)
                ->withHeader('Content-Type', 'application/json')
                ->withBody($factory->createStream((string) json_encode($data, JSON_UNESCAPED_SLASHES)));
        });
    }

    private function embed(RecordingClient $client): Embed
    {
        $crawler = new Crawler($client);
        $crawler->setUrlPolicy(UrlPolicy::default()->withResolver(static function (string $host): array {
            return ['1.1.1.1'];
        }));

        return new Embed($crawler);
    }
}
