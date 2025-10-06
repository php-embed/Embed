<?php
declare(strict_types = 1);

namespace Embed\Adapters\Twitter\Detectors;

use DateTime;
use Embed\Adapters\Twitter\Extractor;
use Embed\Detectors\PublishedTime as Detector;

class PublishedTime extends Detector
{
    public function detect(): ?DateTime
    {
        /** @var Extractor $extractor */
        $extractor = $this->extractor;
        $api = $extractor->getApi();

        $result = $api->time('data', 'created_at');
        return $result !== null ? $result : parent::detect();
    }
}
