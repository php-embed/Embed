<?php
declare(strict_types = 1);

namespace Embed\Tests;

use Brick\VarExporter\VarExporter;
use DateTime;
use Embed\Embed;
use Embed\Extractor;
use Embed\ExtractorFactory;
use Embed\Http\Crawler;
use JsonSerializable;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\UriInterface;

abstract class PagesTestCase extends TestCase
{
    /**
     * Cache mode
     * -1 = Read from cache (throws an exception if the cache doesn't exist)
     *  0 = Read from cache (generate the files the first time)
     *  1 = Read from net without overriding the cache files
     *  2 = Read from net and override the cache files
     *
     * Can be overridden by environment variables:
     * - UPDATE_EMBED_SNAPSHOTS=1 : Fetch from network and update both cache and fixtures
     * - EMBED_STRICT_CACHE=1 : Fail if cache or fixture doesn't exist (mode -1)
     */
    const CACHE = 0;

    /**
     * Fixtures mode
     * 0 = Do not override the fixtures
     * 1 = Override the fixtures
     *
     * Can be overridden by environment variable:
     * - UPDATE_EMBED_SNAPSHOTS=1 : Update both cache and fixtures
     */
    const FIXTURES = 0;

    private const DETECTORS = [
        'authorName',
        'authorUrl',
        'cms',
        'code',
        'description',
        'favicon',
        'feeds',
        'icon',
        'image',
        'keywords',
        'language',
        'languages',
        'license',
        'providerName',
        'providerUrl',
        'publishedTime',
        'redirect',
        'title',
        'url',
    ];

    private static Embed $embed;

    /**
     * Get cache mode from environment variables or class constant
     */
    protected static function getCacheMode(): int
    {
        // UPDATE_EMBED_SNAPSHOTS=1 : Fetch from network and override cache
        if (getenv('UPDATE_EMBED_SNAPSHOTS')) {
            return 2;
        }

        // EMBED_STRICT_CACHE=1 : Fail if cache doesn't exist
        if (getenv('EMBED_STRICT_CACHE')) {
            return -1;
        }

        return static::CACHE;
    }

    /**
     * Get fixtures mode from environment variable or class constant
     */
    protected static function getFixturesMode(): int
    {
        // UPDATE_EMBED_SNAPSHOTS=1 : Override fixtures with new results
        if (getenv('UPDATE_EMBED_SNAPSHOTS')) {
            return 1;
        }

        return static::FIXTURES;
    }

    /**
     * Check if strict cache mode is enabled
     */
    protected static function isStrictMode(): bool
    {
        return (bool) getenv('EMBED_STRICT_CACHE');
    }

    private static function getEmbed(): Embed
    {
        if (isset(self::$embed)) {
            return self::$embed;
        }

        $dispatcher = new FileClient(__DIR__.'/cache');
        $dispatcher->setMode(self::getCacheMode());

        return self::$embed = new Embed(new Crawler($dispatcher), self::getExtractorFactory());
    }

    protected function assertEmbed(string $url)
    {
        $embed = self::getEmbed();
        $extractor = $embed->get($url);

        $uri = $extractor->getRequest()->getUri();
        $data = self::getData($extractor);
        $expected = self::readData($uri);

        // In strict mode, fail if fixture is missing
        if (!$expected && self::isStrictMode()) {
            $this->fail("Fixture not found for {$url}");
        }

        // Update mode: save fixture and skip
        if (self::getFixturesMode() === 1) {
            self::writeData($uri, $data);
            echo PHP_EOL."Save fixture: {$url}";
            $this->markTestSkipped('Skipped assertion for '.$url);
        } elseif (!$expected) {
            // Missing fixture in non-strict mode: create and skip
            self::writeData($uri, $data);
            echo PHP_EOL."Save fixture: {$url}";
            $this->markTestSkipped('Skipped assertion for '.$url);
        } else {
            $this->assertEquals($expected, $data, $url);
        }
    }

    private static function writeData(UriInterface $uri, array $data): void
    {
        $filename = __DIR__.'/fixtures/'.FileClient::getFileName($uri);

        file_put_contents(
            $filename,
            sprintf("<?php\ndeclare(strict_types = 1);\n\nreturn %s;\n", VarExporter::export($data))
        );
    }

    private static function readData(UriInterface $uri): ?array
    {
        $filename = __DIR__.'/fixtures/'.FileClient::getFileName($uri);

        if (is_file($filename)) {
            return require $filename;
        }

        return null;
    }

    private static function getData(Extractor $extractor): array
    {
        $data = [];

        foreach (self::DETECTORS as $name) {
            $data[$name] = self::convert($extractor->$name);
        }

        $data['linkedData'] = $extractor->getLinkedData()->all();
        $data['oEmbed'] = $extractor->getOEmbed()->all();

        if (method_exists($extractor, 'getApi')) {
            $data['api'] = $extractor->getApi()->all();
        }
        $data['allLinkedData'] = $extractor->getLinkedData()->getAll();

        return $data;
    }

    private static function convert($value)
    {
        if (!$value) {
            return $value;
        }

        if ($value instanceof JsonSerializable) {
            return $value->jsonSerialize();
        }

        if ($value instanceof UriInterface) {
            return (string) $value;
        }

        if ($value instanceof DateTime) {
            return $value->format('Y-m-d H:i:s');
        }

        if (is_array($value)) {
            return array_map(__METHOD__, $value);
        }

        return $value;
    }

    private static function getExtractorFactory()
    {
        $settings = [
            'instagram:token' => $_ENV['INSTAGRAM_TOKEN'] ?? null,
            'facebook:token' => $_ENV['FACEBOOK_TOKEN'] ?? null,
        ];

        return new ExtractorFactory($settings);
    }
}
