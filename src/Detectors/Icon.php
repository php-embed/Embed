<?php
declare(strict_types = 1);

namespace Embed\Detectors;

use Psr\Http\Message\UriInterface;

class Icon extends Detector
{
    public function detect(): ?UriInterface
    {
        $document = $this->extractor->getDocument();

        $result = $document->link('apple-touch-icon-precomposed');
        if ($result !== null) {
            return $result;
        }

        $result = $document->link('apple-touch-icon');
        if ($result !== null) {
            return $result;
        }

        $result = $document->link('icon', ['sizes' => '144x144']);
        if ($result !== null) {
            return $result;
        }

        $result = $document->link('icon', ['sizes' => '96x96']);
        if ($result !== null) {
            return $result;
        }

        return $document->link('icon', ['sizes' => '48x48']);
    }
}
