<?php
declare(strict_types = 1);

namespace Embed\Adapters\Kick\Detectors;

use Embed\Detectors\AuthorName as Detector;

/**
 * @extends Detector<\Embed\Adapters\Kick\Extractor>
 */
class AuthorName extends Detector
{
    public function detect(): ?string
    {
        $api = $this->extractor->getApi();

        return $api->str('livestream', 'channel', 'user', 'username')
            ?? parent::detect();
    }
}
