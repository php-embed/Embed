<?php
declare(strict_types = 1);

namespace Embed\Detectors;

class License extends Detector
{
    public function detect(): ?string
    {
        $oembed = $this->extractor->getOEmbed();
        $metas = $this->extractor->getMetas();

        $license = $oembed->str('license_url');
        return $license !== null ? $license : $metas->str('copyright');
    }
}
