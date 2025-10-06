<?php
declare(strict_types = 1);

namespace Embed\Detectors;

use Psr\Http\Message\UriInterface;

class Favicon extends Detector
{
    public function detect(): UriInterface
    {
        $document = $this->extractor->getDocument();

        $result = $document->link('shortcut icon');
        if ($result !== null) {
            return $result;
        }

        $result = $document->link('icon');
        if ($result !== null) {
            return $result;
        }

        return $this->extractor->getUri()->withPath('/favicon.ico')->withQuery('');
    }
}
