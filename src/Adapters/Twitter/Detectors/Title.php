<?php
declare(strict_types = 1);

namespace Embed\Adapters\Twitter\Detectors;

use Embed\Adapters\Twitter\Extractor;
use Embed\Detectors\Title as Detector;

class Title extends Detector
{
    public function detect(): ?string
    {
        /** @var Extractor $extractor */
        $extractor = $this->extractor;
        $api = $extractor->getApi();
        $name = $api->str('includes', 'users', '0', 'name');

        if ($name !== null) {
            return "Tweet by $name";
        }

        return parent::detect();
    }
}
