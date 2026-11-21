<?php
declare(strict_types = 1);

namespace Embed\Adapters\Youtube\Detectors;

use Embed\Detectors\Image as Detector;
use Psr\Http\Message\UriInterface;

/**
 * @extends Detector<\Embed\Adapters\Youtube\Extractor>
 */
class Image extends Detector
{
    public function detect(): ?UriInterface
    {
        $image = parent::detect();

        return $this->detectMaxRes($image) ?? $image;
    }

    /**
     * YouTube's oEmbed thumbnail is always hqdefault.jpg (480x360, with black
     * bars on 16:9 videos). The page links maxresdefault.jpg in og:image only
     * when the video has it, so use it when it belongs to the same video.
     */
    private function detectMaxRes(?UriInterface $image): ?UriInterface
    {
        if ($image === null || $image->getHost() !== 'i.ytimg.com') {
            return null;
        }

        if (preg_match('#^/vi/([^/]+)/hqdefault\.jpg$#', $image->getPath(), $matches) !== 1) {
            return null;
        }

        $metas = $this->extractor->getMetas();

        foreach (['og:image', 'twitter:image'] as $key) {
            $url = $metas->url($key);

            if (
                $url !== null
                && $url->getHost() === 'i.ytimg.com'
                && $url->getPath() === "/vi/{$matches[1]}/maxresdefault.jpg"
            ) {
                return $url;
            }
        }

        return null;
    }
}
