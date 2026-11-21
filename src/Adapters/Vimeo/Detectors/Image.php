<?php
declare(strict_types = 1);

namespace Embed\Adapters\Vimeo\Detectors;

use Embed\Detectors\Image as Detector;
use Psr\Http\Message\UriInterface;

/**
 * @extends Detector<\Embed\Adapters\Vimeo\Extractor>
 */
class Image extends Detector
{
    public function detect(): ?UriInterface
    {
        $image = parent::detect();

        if ($image === null || $image->getHost() !== 'i.vimeocdn.com') {
            return $image;
        }

        return self::fullSize($image);
    }

    /**
     * Vimeo's oEmbed thumbnail is resized to the player width
     * (like .../video/123-abc-d_295x166). Without the size suffix,
     * Vimeo's CDN serves the image at its full size.
     */
    public static function fullSize(UriInterface $image): UriInterface
    {
        if (preg_match('#^(/video/[^/_]+)_\d+(?:x\d+)?(\.[a-z]+)?$#i', $image->getPath(), $matches) !== 1) {
            return $image;
        }

        return $image->withPath($matches[1].($matches[2] ?? ''));
    }
}
