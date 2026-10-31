<?php
declare(strict_types = 1);

namespace Embed\Adapters\Kick\Detectors;

use Embed\Detectors\Image as Detector;
use Psr\Http\Message\UriInterface;

/**
 * @extends Detector<\Embed\Adapters\Kick\Extractor>
 */
class Image extends Detector
{
    public function detect(): ?UriInterface
    {
        $api = $this->extractor->getApi();

        return $api->url('list_item', 'thumbnail', 'src')
            ?? $api->url('livestream', 'thumbnail')
            ?? parent::detect();
    }
}
