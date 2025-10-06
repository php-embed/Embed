<?php
declare(strict_types = 1);

namespace Embed\Adapters\Wikipedia\Detectors;

use Embed\Adapters\Wikipedia\Extractor;
use Embed\Detectors\Title as Detector;

class Title extends Detector
{
    public function detect(): ?string
    {
        /** @var Extractor $extractor */
        $extractor = $this->extractor;
        $api = $extractor->getApi();

        $result = $api->str('title');
        return (is_string($result) && trim($result) !== '') ? $result : parent::detect();
    }
}
