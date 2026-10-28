<?php
declare(strict_types = 1);

namespace Embed\Adapters\Bilibili\Detectors;

use Embed\Detectors\Description as Detector;

/**
 * @extends Detector<\Embed\Adapters\Bilibili\Extractor>
 */
class Description extends Detector
{
    public function detect(): ?string
    {
        if ($this->extractor->isErrorPage()) {
            return null;
        }

        return parent::detect();
    }
}
