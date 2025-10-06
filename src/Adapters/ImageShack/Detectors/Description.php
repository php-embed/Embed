<?php
declare(strict_types = 1);

namespace Embed\Adapters\ImageShack\Detectors;

use Embed\Adapters\ImageShack\Extractor;
use Embed\Detectors\Description as Detector;

class Description extends Detector
{
    public function detect(): ?string
    {
        /** @var Extractor $extractor */
        $extractor = $this->extractor;
        $api = $extractor->getApi();

        $result = $api->str('description');
        return (is_string($result) && trim($result) !== '') ? $result : parent::detect();
    }
}
