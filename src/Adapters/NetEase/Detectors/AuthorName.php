<?php
declare(strict_types = 1);

namespace Embed\Adapters\NetEase\Detectors;

use Embed\Detectors\AuthorName as Detector;

/**
 * @extends Detector<\Embed\Adapters\NetEase\Extractor>
 */
class AuthorName extends Detector
{
    public function detect(): ?string
    {
        $result = parent::detect();

        if ($result !== null) {
            return $result;
        }

        $description = $this->extractor->getMetas()->str('description', 'og:description');

        if ($description !== null && preg_match('/由\s*(.+?)\s*演唱/u', $description, $matches) === 1) {
            return $matches[1];
        }

        return null;
    }
}
