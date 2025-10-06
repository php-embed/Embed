<?php
declare(strict_types = 1);

namespace Embed\Detectors;

class Description extends Detector
{
    public function detect(): ?string
    {
        $oembed = $this->extractor->getOEmbed();
        $metas = $this->extractor->getMetas();
        $ld = $this->extractor->getLinkedData();

        $result = $oembed->str('description');
        if (is_string($result) && trim($result) !== '') {
            return $result;
        }

        $result = $metas->str(
            'og:description',
            'twitter:description',
            'lp:description',
            'description',
            'article:description',
            'dcterms.description',
            'sailthru.description',
            'excerpt',
            'article.summary'
        );
        if (is_string($result) && trim($result) !== '') {
            return $result;
        }

        return $ld->str('description');
    }
}
