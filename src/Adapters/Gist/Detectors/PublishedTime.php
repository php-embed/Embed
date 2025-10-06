<?php
declare(strict_types = 1);

namespace Embed\Adapters\Gist\Detectors;

use DateTime;
use Embed\Adapters\Gist\Extractor;
use Embed\Detectors\PublishedTime as Detector;

class PublishedTime extends Detector
{
    public function detect(): ?DateTime
    {
        /** @var Extractor $extractor */
        $extractor = $this->extractor;
        $api = $extractor->getApi();

        $result = $api->time('created_at');
        return $result !== null ? $result : parent::detect();
    }
}
