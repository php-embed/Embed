<?php
declare(strict_types = 1);

namespace Embed\Adapters\Bilibili\Detectors;

use Embed\Detectors\Title as Detector;

/**
 * @extends Detector<\Embed\Adapters\Bilibili\Extractor>
 */
class Title extends Detector
{
    public function detect(): ?string
    {
        if ($this->extractor->isErrorPage()) {
            return null;
        }

        return parent::detect();
    }
}
