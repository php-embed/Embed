<?php
declare(strict_types = 1);

namespace Embed\Adapters\Archive\Detectors;

use DateTime;
use Embed\Adapters\Archive\Extractor;
use Embed\Detectors\PublishedTime as Detector;

class PublishedTime extends Detector
{
    public function detect(): ?DateTime
    {
        /** @var Extractor $extractor */
        $extractor = $this->extractor;
        $api = $extractor->getApi();

        $fields = ['publicdate', 'addeddate', 'date'];
        foreach ($fields as $field) {
            $result = $api->time('metadata', $field);
            if ($result !== null) {
                return $result;
            }
        }

        return parent::detect();
    }
}
