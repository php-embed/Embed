<?php
declare(strict_types = 1);

namespace Embed\Adapters\Kick\Detectors;

use Embed\Detectors\Title as Detector;

/**
 * @extends Detector<\Embed\Adapters\Kick\Extractor>
 */
class Title extends Detector
{
    public function detect(): ?string
    {
        $api = $this->extractor->getApi();

        return $api->str('livestream', 'session_title')
            ?? $api->str('list_item', 'session_title')
            ?? parent::detect();
    }
}
