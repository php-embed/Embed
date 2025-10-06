<?php
declare(strict_types = 1);

namespace Embed\Adapters\Wikipedia\Detectors;

use Embed\Adapters\Wikipedia\Extractor;
use Embed\Detectors\Description as Detector;

class Description extends Detector
{
    public function detect(): ?string
    {
        /** @var Extractor $extractor */
        $extractor = $this->extractor;
        $api = $extractor->getApi();

        $result = $api->str('extract');
        return (is_string($result) && trim($result) !== '') ? $result : parent::detect();
    }
}
