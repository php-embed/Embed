<?php
declare(strict_types = 1);

namespace Embed\Detectors;

class Title extends Detector
{
    public function detect(): ?string
    {
        $oembed = $this->extractor->getOEmbed();
        $document = $this->extractor->getDocument();
        $metas = $this->extractor->getMetas();

        $result = $oembed->str('title');
        if (is_string($result) && trim($result) !== '') {
            return $result;
        }

        $result = $metas->str(
            'og:title',
            'twitter:title',
            'lp:title',
            'dcterms.title',
            'article:title',
            'headline',
            'article.headline',
            'parsely-title'
        );
        if (is_string($result) && trim($result) !== '') {
            return $result;
        }

        return $document->select('.//head/title')->str();
    }
}
