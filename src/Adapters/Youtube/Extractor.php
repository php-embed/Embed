<?php
declare(strict_types = 1);

namespace Embed\Adapters\Youtube;

use Embed\Extractor as Base;

/**
 * @template-extends Base<\Embed\Detectors\Detector<self>>
 */
class Extractor extends Base
{
    public function createCustomDetectors(): array
    {
        return [
            'feeds' => new Detectors\Feeds($this),
            'image' => new Detectors\Image($this),
            'code' => new Detectors\Code($this),
        ];
    }
}
