<?php
declare(strict_types = 1);

namespace Embed\Adapters\NetEase;

use Embed\Extractor as Base;
use Psr\Http\Message\UriInterface;

/**
 * @template-extends Base<\Embed\Detectors\Detector<self>>
 */
class Extractor extends Base
{
    /** Player types used by music.163.com/outchain/player */
    private const TYPES = [
        'song' => 2,
        'album' => 1,
        'playlist' => 0,
    ];

    public function createCustomDetectors(): array
    {
        return [
            'code' => new Detectors\Code($this),
            'authorName' => new Detectors\AuthorName($this),
        ];
    }

    /**
     * NetEase Music URLs like /#/song?id=1 keep the route in the fragment,
     * which is never sent to the server. Move it to the path and query.
     */
    public static function normalizeUri(UriInterface $uri): UriInterface
    {
        $fragment = $uri->getFragment();

        if (preg_match('#^/(?:m/)?(song|album|playlist)\?(.*)$#', $fragment, $matches) !== 1) {
            return $uri;
        }

        return $uri->withPath('/'.$matches[1])->withQuery($matches[2])->withFragment('');
    }

    /**
     * Returns the player type and the id of a song, album or playlist.
     *
     * @return array{0: int, 1: string}|null
     */
    public static function parseUri(UriInterface $uri): ?array
    {
        $path = $uri->getPath();
        $type = self::TYPES[trim($path, '/')] ?? null;

        if ($type === null) {
            return null;
        }

        parse_str($uri->getQuery(), $query);
        $id = $query['id'] ?? null;

        if (!is_string($id) || preg_match('/^\d+$/', $id) !== 1) {
            return null;
        }

        return [$type, $id];
    }
}
