<?php
declare(strict_types = 1);

namespace Embed\Adapters\TencentVideo;

use Embed\Extractor as Base;
use Psr\Http\Message\UriInterface;

/**
 * @template-extends Base<\Embed\Detectors\Detector<self>>
 */
class Extractor extends Base
{
    public function createCustomDetectors(): array
    {
        return [
            'code' => new Detectors\Code($this),
        ];
    }

    /**
     * Returns the video id (vid) of v.qq.com/x/cover/<cid>/<vid>.html,
     * v.qq.com/x/page/<vid>.html and v.qq.com/x/cover/<cid>.html?vid=<vid> URLs.
     * Cover URLs without a vid point to a series, not to one video.
     */
    public static function parseVideoId(UriInterface $uri): ?string
    {
        if (preg_match('#^/x/(?:cover/[0-9a-z]+|page)/([0-9a-z]{11})\.html$#i', $uri->getPath(), $matches) === 1) {
            return $matches[1];
        }

        if (preg_match('#^/x/cover/[0-9a-z]+\.html$#i', $uri->getPath()) !== 1) {
            return null;
        }

        parse_str($uri->getQuery(), $query);
        $vid = $query['vid'] ?? null;

        if (is_string($vid) && preg_match('/^[0-9a-z]{11}$/i', $vid) === 1) {
            return $vid;
        }

        return null;
    }
}
