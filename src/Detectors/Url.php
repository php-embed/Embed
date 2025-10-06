<?php
declare(strict_types = 1);

namespace Embed\Detectors;

use Psr\Http\Message\UriInterface;

class Url extends Detector
{
    public function detect(): UriInterface
    {
        $oembed = $this->extractor->getOEmbed();

        $result = $oembed->url('url');
        if ($result !== null) {
            return $result;
        }

        $result = $oembed->url('web_page');
        if ($result !== null) {
            return $result;
        }

        return $this->extractor->getUri();
    }
}
