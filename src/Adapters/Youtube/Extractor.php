<?php
declare(strict_types = 1);

namespace Embed\Adapters\Youtube;

use Embed\Extractor as Base;

class Extractor extends Base
{
    /**
     * @return array<string, \Embed\Detectors\Detector>
     */
    public function createCustomDetectors(): array
    {
        return [
            'feeds' => new Detectors\Feeds($this),
        ];
    }
}
