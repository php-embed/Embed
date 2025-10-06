<?php
declare(strict_types = 1);

namespace Embed\Adapters\Twitter\Detectors;

use Embed\Adapters\Twitter\Extractor;
use Embed\Detectors\AuthorName as Detector;

class AuthorName extends Detector
{
    public function detect(): ?string
    {
        /** @var Extractor $extractor */
        $extractor = $this->extractor;
        $api = $extractor->getApi();

        $result = $api->str('includes', 'users', '0', 'name');
        return (is_string($result) && trim($result) !== '') ? $result : parent::detect();
    }
}
