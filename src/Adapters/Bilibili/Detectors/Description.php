<?php
declare(strict_types = 1);

namespace Embed\Adapters\Bilibili\Detectors;

use Embed\Adapters\Bilibili\Extractor;
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

        $description = parent::detect();

        if ($description !== null && Extractor::isTemplate($description)) {
            return null;
        }

        return $description;
    }
}
