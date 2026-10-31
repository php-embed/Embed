<?php
declare(strict_types = 1);

namespace Embed\Adapters\Kick;

use Embed\Extractor as Base;
use Embed\Http\Crawler;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\UriInterface;

/**
 * @template-extends Base<\Embed\Detectors\Detector<self>>
 */
class Extractor extends Base
{
    /** Top level pages that are not channels */
    private const RESERVED_PATHS = ['browse', 'categories', 'category', 'following', 'search', 'dashboard', 'settings', 'privacy-policy', 'terms-of-service', 'community-guidelines'];

    private Api $api;

    public function __construct(
        UriInterface $uri,
        RequestInterface $request,
        ResponseInterface $response,
        Crawler $crawler
    ) {
        parent::__construct($uri, $request, $response, $crawler);
        $this->api = new Api($this);
    }

    public function getApi(): Api
    {
        return $this->api;
    }

    public function createCustomDetectors(): array
    {
        return [
            'authorName' => new Detectors\AuthorName($this),
            'code' => new Detectors\Code($this),
            'image' => new Detectors\Image($this),
            'title' => new Detectors\Title($this),
        ];
    }

    /**
     * Returns the channel slug and the VOD uuid of a video URL.
     *
     * @return array{0: string, 1: string}|null
     */
    public static function parseVideoPath(string $path): ?array
    {
        if (preg_match('#^/([\w-]+)/videos/([0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12})/?$#i', $path, $matches) === 1) {
            return [$matches[1], strtolower($matches[2])];
        }

        return null;
    }

    public static function parseChannelPath(string $path): ?string
    {
        if (preg_match('#^/([\w-]+)/?$#', $path, $matches) === 1 && !in_array(strtolower($matches[1]), self::RESERVED_PATHS, true)) {
            return $matches[1];
        }

        return null;
    }
}
