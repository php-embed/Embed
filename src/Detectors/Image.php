<?php
declare(strict_types = 1);

namespace Embed\Detectors;

use Psr\Http\Message\UriInterface;

class Image extends Detector
{
    public function detect(): ?UriInterface
    {
        $oembed = $this->extractor->getOEmbed();
        $document = $this->extractor->getDocument();
        $metas = $this->extractor->getMetas();
        $ld = $this->extractor->getLinkedData();

        $result = $oembed->url('image');
        if ($result !== null) {
            return $result;
        }

        $result = $oembed->url('thumbnail');
        if ($result !== null) {
            return $result;
        }

        $result = $oembed->url('thumbnail_url');
        if ($result !== null) {
            return $result;
        }

        $result = $metas->url('og:image', 'og:image:url', 'og:image:secure_url', 'twitter:image', 'twitter:image:src', 'lp:image');
        if ($result !== null) {
            return $result;
        }

        $result = $document->link('image_src');
        if ($result !== null) {
            return $result;
        }

        $result = $ld->url('image.url');
        if ($result !== null) {
            return $result;
        }

        return $this->detectFromContentType();
    }

    private function detectFromContentType(): ?\Psr\Http\Message\UriInterface
    {
        if (!$this->extractor->getResponse()->hasHeader('content-type')) {
            return null;
        }

        $contentType = $this->extractor->getResponse()->getHeader('content-type')[0];

        if (strpos($contentType, 'image/') === 0) {
            return $this->extractor->getUri();
        }

        return null;
    }
}
