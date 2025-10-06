<?php
declare(strict_types = 1);

namespace Embed\Detectors;

class AuthorName extends Detector
{
    public function detect(): ?string
    {
        $oembed = $this->extractor->getOEmbed();
        $metas = $this->extractor->getMetas();

        $result = $oembed->str('author_name');
        if (is_string($result) && trim($result) !== '') {
            return $result;
        }

        return $metas->str(
            'article:author',
            'book:author',
            'sailthru.author',
            'lp.article:author',
            'twitter:creator',
            'dcterms.creator',
            'author'
        );
    }
}
