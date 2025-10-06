<?php
declare(strict_types = 1);

namespace Embed\Detectors;

use function Embed\isEmpty;

class Languages extends Detector
{
    /**
     * @return array<string, \Psr\Http\Message\UriInterface>
     */
    public function detect(): array
    {
        $document = $this->extractor->getDocument();
        $languages = [];

        foreach ($document->select('.//link[@hreflang]')->nodes() as $node) {
            if (!$node instanceof \DOMElement) {
                continue;
            }

            $language = $node->getAttribute('hreflang');
            $href = $node->getAttribute('href');

            if (isEmpty($language, $href)) {
                continue;
            }

            $languages[$language] = $this->extractor->resolveUri($href);
        }

        return $languages;
    }
}
