<?php
declare(strict_types = 1);

namespace Embed\Adapters\Twitter\Detectors;

use Embed\Adapters\Twitter\Extractor;
use Embed\Detectors\Image as Detector;
use Psr\Http\Message\UriInterface;

class Image extends Detector
{
    public function detect(): ?UriInterface
    {
        /** @var Extractor $extractor */
        $extractor = $this->extractor;
        $api = $extractor->getApi();
        $preview = $api->url('includes', 'media', '0', 'preview_image_url');

        if ($preview !== null) {
            return $preview;
        }

        $regular = $api->url('includes', 'media', '0', 'url');

        if ($regular !== null) {
            return $regular;
        }

        return parent::detect();
    }
}
