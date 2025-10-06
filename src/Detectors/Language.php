<?php
declare(strict_types = 1);

namespace Embed\Detectors;

class Language extends Detector
{
    public function detect(): ?string
    {
        $document = $this->extractor->getDocument();
        $metas = $this->extractor->getMetas();
        $ld = $this->extractor->getLinkedData();

        $result = $document->select('/html')->str('lang');
        if (is_string($result) && trim($result) !== '') {
            return $result;
        }

        $result = $document->select('/html')->str('xml:lang');
        if (is_string($result) && trim($result) !== '') {
            return $result;
        }

        $result = $metas->str('language', 'lang', 'og:locale', 'dc:language');
        if (is_string($result) && trim($result) !== '') {
            return $result;
        }

        $result = $document->select('.//meta', ['http-equiv' => 'content-language'])->str('content');
        if (is_string($result) && trim($result) !== '') {
            return $result;
        }

        return $ld->str('inLanguage');
    }
}
