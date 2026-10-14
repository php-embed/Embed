<?php
declare(strict_types = 1);

namespace Embed\Tests;

use Embed\Embed;
use Embed\Http\BlockedRequestException;
use Embed\Http\Crawler;
use Embed\Http\UrlPolicy;
use Nyholm\Psr7\Factory\Psr17Factory;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class SsrfEmbedTest extends TestCase
{
    public function testRelativeResourcesStayAbsoluteAndAreNotFetched(): void
    {
        $html = <<<'HTML'
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Page title</title>
<meta property="og:image" content="/images/pic.jpg">
<link rel="icon" href="//cdn.example/favicon.ico">
<link rel="alternate" hreflang="es" href="/es">
<link rel="alternate" type="application/json+oembed" href="/oembed.json">
</head>
<body></body>
</html>
HTML;
        $client = $this->client(static function (RequestInterface $request) use ($html): ResponseInterface {
            $factory = new Psr17Factory();
            if ($request->getUri()->getPath() === '/oembed.json') {
                return $factory->createResponse(200)
                    ->withHeader('Content-Type', 'application/json')
                    ->withBody($factory->createStream('{"title":"From oEmbed","type":"link"}'));
            }

            return $factory->createResponse(200)
                ->withHeader('Content-Type', 'text/html')
                ->withBody($factory->createStream($html));
        });

        $info = $this->embed($client)->get('http://public.test/article');

        $this->assertSame('From oEmbed', $info->title);
        $this->assertSame('http://public.test/images/pic.jpg', (string) $info->image);
        $this->assertSame('http://cdn.example/favicon.ico', (string) $info->favicon);
        $this->assertSame('http://public.test/es', (string) $info->languages['es']);

        $paths = array_map(static function (RequestInterface $request): string {
            return $request->getUri()->getHost().$request->getUri()->getPath();
        }, $client->requests);
        $this->assertSame(['public.test/article', 'public.test/oembed.json'], $paths);
    }

    public function testBlockedOEmbedEndpointIsSkipped(): void
    {
        $html = '<html><head><title>Stay here</title>'
            .'<link rel="alternate" type="application/json+oembed" href="http://127.0.0.1/leak.json">'
            .'</head></html>';
        $client = $this->htmlClient($html);

        $info = $this->embed($client)->get('http://public.test/page');

        $this->assertSame('Stay here', $info->title);
        $this->assertSame([], $info->getOEmbed()->all());
        $this->assertCount(1, $client->requests);
        $this->assertSame('public.test', $client->requests[0]->getUri()->getHost());
    }

    public function testBlockedMetaRefreshIsIgnored(): void
    {
        $html = '<html><head><title>Stay</title>'
            .'<meta http-equiv="refresh" content="0;url=http://127.0.0.1/internal">'
            .'</head></html>';
        $client = $this->htmlClient($html);

        $info = $this->embed($client)->get('http://public.test/refresh');

        $this->assertSame('Stay', $info->title);
        $this->assertCount(1, $client->requests);
    }

    public function testCustomClientDoesNotReceiveABlockedUrl(): void
    {
        $client = $this->htmlClient('<html></html>');

        try {
            $this->embed($client)->get('http://127.0.0.1/secret');
            $this->fail('The main URL must be rejected.');
        } catch (BlockedRequestException $exception) {
            $this->assertSame('non_public_address', $exception->getReason());
        }

        $this->assertSame([], $client->requests);
    }

    public function testCustomClientValidatesEveryParallelUrlBeforeSending(): void
    {
        $client = $this->htmlClient('<html></html>');

        try {
            $this->embed($client)->getMulti('http://public.test/a', 'http://127.0.0.1/secret');
            $this->fail('One blocked URL must reject the batch.');
        } catch (BlockedRequestException $exception) {
            $this->assertSame('non_public_address', $exception->getReason());
        }

        $this->assertSame([], $client->requests);
    }

    private function embed(RecordingClient $client): Embed
    {
        $crawler = new Crawler($client);
        $crawler->setUrlPolicy(UrlPolicy::default()->withResolver(static function (string $host): array {
            return ['1.1.1.1'];
        }));

        return new Embed($crawler);
    }

    private function htmlClient(string $html): RecordingClient
    {
        return $this->client(static function (RequestInterface $request) use ($html): ResponseInterface {
            $factory = new Psr17Factory();

            return $factory->createResponse(200)
                ->withHeader('Content-Type', 'text/html')
                ->withBody($factory->createStream($html));
        });
    }

    /**
     * @param callable(RequestInterface): ResponseInterface $handler
     */
    private function client(callable $handler): RecordingClient
    {
        return new RecordingClient($handler);
    }
}
