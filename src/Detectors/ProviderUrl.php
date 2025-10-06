<?php
declare(strict_types = 1);

namespace Embed\Detectors;

use Psr\Http\Message\UriInterface;

class ProviderUrl extends Detector
{
    public function detect(): UriInterface
    {
        $oembed = $this->extractor->getOEmbed();
        $metas = $this->extractor->getMetas();

        $result = $oembed->url('provider_url');
        if ($result !== null) {
            return $result;
        }

        $result = $metas->url('og:website');
        if ($result !== null) {
            return $result;
        }

        return $this->fallback();
    }

    private function fallback(): UriInterface
    {
        return $this->extractor->getUri()->withPath('')->withQuery('')->withFragment('');
    }
}
