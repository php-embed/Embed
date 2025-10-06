<?php
declare(strict_types = 1);

namespace Embed\Adapters\ImageShack\Detectors;

use DateTime;
use Embed\Adapters\ImageShack\Extractor;
use Embed\Detectors\PublishedTime as Detector;

class PublishedTime extends Detector
{
    public function detect(): ?DateTime
    {
        /** @var Extractor $extractor */
        $extractor = $this->extractor;
        $api = $extractor->getApi();

        $result = $api->time('creation_date');
        return $result !== null ? $result : parent::detect();
    }
}
