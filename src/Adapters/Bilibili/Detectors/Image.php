<?php
declare(strict_types = 1);

namespace Embed\Adapters\Bilibili\Detectors;

use Embed\Detectors\Image as Detector;
use Psr\Http\Message\UriInterface;

/**
 * @extends Detector<\Embed\Adapters\Bilibili\Extractor>
 */
class Image extends Detector
{
    public function detect(): ?UriInterface
    {
        if ($this->extractor->isErrorPage()) {
            return null;
        }

        return parent::detect();
    }
}
