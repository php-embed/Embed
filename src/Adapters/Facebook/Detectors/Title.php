<?php
declare(strict_types = 1);

namespace Embed\Adapters\Facebook\Detectors;

use Embed\Detectors\Title as Detector;

class Title extends Detector
{
    /**
     * Do not use og:title and twitter:title
     */
    public function detect(): ?string
    {
        $document = $this->extractor->getDocument();
        $oembed = $this->extractor->getOEmbed();

        $result = $oembed->str('title');
        return $result !== null ? $result : $document->select('.//head/title')->str();
    }
}
