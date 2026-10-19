<?php
declare(strict_types = 1);

namespace Embed\Tests;

use DateTime;
use Embed\Embed;
use Embed\Http\Crawler;
use Embed\Http\UrlPolicy;
use Embed\JsonLd\DocumentLoader;
use ML\JsonLD\Exception\JsonLdException;
use Nyholm\Psr7\Factory\Psr17Factory;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class JsonLdRemoteContextTest extends TestCase
{
    private static LocalHttpServer $server;

    public static function setUpBeforeClass(): void
    {
        self::$server = LocalHttpServer::start();
    }

    public static function tearDownAfterClass(): void
    {
        self::$server->stop();
    }

    protected function setUp(): void
    {
        self::$server->clearLog();
    }

    public function testLoopbackContextIsNotRequested(): void
    {
        $context = 'http://127.0.0.1:'.self::$server->port().'/go?to=/secret';
        $client = $this->htmlClient($this->page($context));

        $info = $this->embed($client)->get('http://public.test/article');

        $this->assertSame('Page title', $info->title);
        $this->assertNull($info->publishedTime);
        $this->assertSame([], self::$server->requests());
        $this->assertCount(1, $client->requests);
        $this->assertSame('public.test', $client->requests[0]->getUri()->getHost());
    }

    /**
     * @dataProvider schemaContextUrls
     */
    public function testSchemaOrgContextResolvesWithoutNetwork(string $context): void
    {
        $client = $this->htmlClient($this->page($context));

        $info = $this->embed($client)->get('http://public.test/article');

        $published = $info->publishedTime;
        $this->assertInstanceOf(DateTime::class, $published);
        $this->assertSame('2017-09-14 10:20:35', $published->format('Y-m-d H:i:s'));
        $this->assertSame('2017-09-14T10:20:35+00:00', $info->getLinkedData()->get('datePublished'));
        $this->assertSame([], self::$server->requests());
        $this->assertCount(1, $client->requests);
    }

    public function testSchemaOrgContextInAnArrayStillResolves(): void
    {
        $client = $this->htmlClient($this->page([
            'https://schema.org/',
            ['@language' => 'en'],
        ]));

        $info = $this->embed($client)->get('http://public.test/article');

        $published = $info->publishedTime;
        $this->assertInstanceOf(DateTime::class, $published);
        $this->assertSame('2017-09-14 10:20:35', $published->format('Y-m-d H:i:s'));
        $this->assertSame([], self::$server->requests());
    }

    /**
     * @dataProvider ignoredContexts
     */
    public function testUnknownContextDoesNotBreakExtraction(string $context): void
    {
        $client = $this->htmlClient($this->page($context));

        $info = $this->embed($client)->get('http://public.test/article');

        $this->assertSame('Page title', $info->title);
        $this->assertNull($info->publishedTime);
        $this->assertNull($info->getLinkedData()->get('datePublished'));
        $this->assertSame([], self::$server->requests());
        $this->assertCount(1, $client->requests);
    }

    public function testMalformedJsonLdDoesNotBreakExtraction(): void
    {
        $invalidJson = '<!DOCTYPE html><html><head><title>Page title</title>'
            .'<script type="application/ld+json">{not json</script>'
            .'</head></html>';
        $invalidContext = $this->page(['@vocab' => []]);

        foreach ([$invalidJson, $invalidContext] as $html) {
            self::$server->clearLog();
            $client = $this->htmlClient($html);
            $info = $this->embed($client)->get('http://public.test/article');

            $this->assertSame('Page title', $info->title);
            $this->assertNull($info->publishedTime);
            $this->assertSame([], self::$server->requests());
        }
    }

    public function testLoaderDoesNotReadLocalFilesOrRemoteUrls(): void
    {
        $path = tempnam(sys_get_temp_dir(), 'embed-jsonld-');
        $this->assertIsString($path);
        file_put_contents($path, 'SENTINEL-DO-NOT-LOAD');

        $loader = new DocumentLoader();
        $targets = [
            $path,
            'file://'.$path,
            'http://127.0.0.1:'.self::$server->port().'/secret',
            'https://example.test/context.jsonld',
            'https://schema.org/Person',
        ];

        try {
            foreach ($targets as $target) {
                try {
                    $loader->loadDocument($target);
                    $this->fail('Expected '.$target.' to be refused.');
                } catch (JsonLdException $exception) {
                    $this->assertStringNotContainsString('SENTINEL-DO-NOT-LOAD', $exception->getMessage());
                }
            }

            $remote = $loader->loadDocument('https://schema.org/docs/jsonldcontext.jsonld');
            $this->assertIsObject($remote->document);
            $context = $remote->document->{'@context'};
            $this->assertIsObject($context);
            $this->assertSame('http://schema.org/', $context->{'@vocab'});
            $this->assertSame([], self::$server->requests());
        } finally {
            unlink($path);
        }
    }

    /**
     * @return array<string, array{0: string}>
     */
    public function schemaContextUrls(): array
    {
        $urls = [
            'http://schema.org',
            'https://schema.org',
            'http://schema.org/',
            'https://schema.org/',
            'http://www.schema.org',
            'https://www.schema.org/',
            'http://schema.googleapis.com/',
            'https://schema.googleapis.com',
            'https://SCHEMA.ORG/docs/jsonldcontext.json',
            'https://schema.org/docs/jsonldcontext.jsonld',
            'http://www.schema.org/docs/jsonldcontext.jsonld/',
            'https://schema.googleapis.com/docs/jsonldcontext.jsonld',
            'https://schema.org/docs/jsonldcontext.json.jsonld',
            'https://schema.org/.jsonld',
            'https://schema.org/docs/jsonldcontext.jsonld?hl=en#vocab',
        ];

        $cases = [];
        foreach ($urls as $url) {
            $cases[$url] = [$url];
        }

        return $cases;
    }

    /**
     * @return array<string, array{0: string}>
     */
    public function ignoredContexts(): array
    {
        return [
            'other host' => ['https://example.test/context.jsonld'],
            'schema.org page' => ['https://schema.org/Person'],
            'lookalike host' => ['https://schema.org.evil.com/docs/jsonldcontext.jsonld'],
            'loopback' => ['http://127.0.0.1/context.jsonld'],
            'link local' => ['http://169.254.169.254/latest/meta-data/'],
            'file' => ['file:///etc/passwd'],
        ];
    }

    /**
     * @param string|array<mixed> $context
     */
    private function page($context): string
    {
        $json = json_encode([
            '@context' => $context,
            '@type' => 'Article',
            'datePublished' => '2017-09-14T10:20:35+00:00',
        ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        if (!is_string($json)) {
            $json = '{}';
        }

        return '<!DOCTYPE html><html><head><meta charset="utf-8"><title>Page title</title>'
            .'<script type="application/ld+json">'.$json.'</script>'
            .'</head><body></body></html>';
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
        return new RecordingClient(static function (RequestInterface $request) use ($html): ResponseInterface {
            $factory = new Psr17Factory();

            return $factory->createResponse(200)
                ->withHeader('Content-Type', 'text/html')
                ->withBody($factory->createStream($html));
        });
    }
}
