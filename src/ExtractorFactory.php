<?php
declare(strict_types = 1);

namespace Embed;

use Embed\Http\Crawler;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\UriInterface;

class ExtractorFactory
{
    private string $default = Extractor::class;
    /** @var array<string, class-string<Extractor>> */
    private array $adapters = [
        'slides.com' => Adapters\Slides\Extractor::class,
        'pinterest.com' => Adapters\Pinterest\Extractor::class,
        'flickr.com' => Adapters\Flickr\Extractor::class,
        'snipplr.com' => Adapters\Snipplr\Extractor::class,
        'play.cadenaser.com' => Adapters\CadenaSer\Extractor::class,
        'ideone.com' => Adapters\Ideone\Extractor::class,
        'gist.github.com' => Adapters\Gist\Extractor::class,
        'github.com' => Adapters\Github\Extractor::class,
        'wikipedia.org' => Adapters\Wikipedia\Extractor::class,
        'archive.org' => Adapters\Archive\Extractor::class,
        'sassmeister.com' => Adapters\Sassmeister\Extractor::class,
        'facebook.com' => Adapters\Facebook\Extractor::class,
        'instagram.com' => Adapters\Instagram\Extractor::class,
        'imageshack.com' => Adapters\ImageShack\Extractor::class,
        'youtube.com' => Adapters\Youtube\Extractor::class,
        'twitch.tv' => Adapters\Twitch\Extractor::class,
        'bandcamp.com' => Adapters\Bandcamp\Extractor::class,
        'twitter.com' => Adapters\Twitter\Extractor::class,
        'x.com' => Adapters\Twitter\Extractor::class,
    ];
    /** @var array<string, class-string<Detectors\Detector>> */
    private array $customDetectors = [];
    /** @var array<string, mixed> */
    private array $settings;

    /**
     * @param array<string, mixed>|null $settings
     */
    public function __construct(?array $settings = [])
    {
        $this->settings = $settings ?? [];
    }

    public function createExtractor(UriInterface $uri, RequestInterface $request, ResponseInterface $response, Crawler $crawler): Extractor
    {
        $host = $uri->getHost();
        $class = $this->default;

        foreach ($this->adapters as $adapterHost => $adapter) {
            // Check if $host is the same domain as $adapterHost.
            if ($host === $adapterHost) {
                $class = $adapter;
                break;
            }

            // Check if $host is a subdomain of $adapterHost.
            if (substr($host, -strlen($adapterHost) - 1) === ".{$adapterHost}") {
                $class = $adapter;
                break;
            }
        }

        /** @var Extractor $extractor */
        $extractor = new $class($uri, $request, $response, $crawler);
        $extractor->setSettings($this->settings);

        foreach ($this->customDetectors as $name => $detectorClass) {
            /** @var Detectors\Detector */
            $detector = new $detectorClass($extractor);
            $extractor->addDetector($name, $detector);
        }

        foreach ($extractor->createCustomDetectors() as $name => $detector) {
            $extractor->addDetector($name, $detector);
        }

        return $extractor;
    }

    /**
     * @param class-string<Extractor> $class
     */
    public function addAdapter(string $pattern, string $class): void
    {
        $this->adapters[$pattern] = $class;
    }

    /**
     * @param class-string<Detectors\Detector> $class
     */
    public function addDetector(string $name, string $class): void
    {
        $this->customDetectors[$name] = $class;
    }

    public function removeAdapter(string $pattern): void
    {
        unset($this->adapters[$pattern]);
    }

    public function setDefault(string $class): void
    {
        $this->default = $class;
    }

    /**
     * @param array<string, mixed> $settings
     */
    public function setSettings(array $settings): void
    {
        $this->settings = $settings;
    }
}
