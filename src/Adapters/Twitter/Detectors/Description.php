<?php
declare(strict_types = 1);

namespace Embed\Adapters\Twitter\Detectors;

use Embed\Adapters\Twitter\Extractor;
use Embed\Detectors\Description as Detector;

class Description extends Detector
{
    public function detect(): ?string
    {
        /** @var Extractor $extractor */
        $extractor = $this->extractor;
        $api = $extractor->getApi();

        $result = $api->str('data', 'text');
        return (is_string($result) && trim($result) !== '') ? $result : parent::detect();
    }
}
