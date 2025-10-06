<?php
declare(strict_types = 1);

namespace Embed\Adapters\Facebook;

use Embed\Extractor as Base;

class Extractor extends Base
{
    /**
     * @return array<string, \Embed\Detectors\Detector>
     */
    public function createCustomDetectors(): array
    {
        $this->oembed = new OEmbed($this);

        return [
            'title' => new Detectors\Title($this),
        ];
    }
}
