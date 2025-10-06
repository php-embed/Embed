<?php
declare(strict_types = 1);

namespace Embed\Detectors;

class ProviderName extends Detector
{
    /** @var string[] */
    private static array $suffixes;

    public function detect(): string
    {
        $oembed = $this->extractor->getOEmbed();
        $metas = $this->extractor->getMetas();

        $result = $oembed->str('provider_name');
        if (is_string($result) && trim($result) !== '') {
            return $result;
        }

        $result = $metas->str(
            'og:site_name',
            'dcterms.publisher',
            'publisher',
            'article:publisher'
        );
        if (is_string($result) && trim($result) !== '') {
            return $result;
        }

        return ucfirst($this->fallback());
    }

    private function fallback(): string
    {
        $host = $this->extractor->getUri()->getHost();

        $host = array_reverse(explode('.', $host));

        switch (count($host)) {
            case 1:
                return $host[0];
            case 2:
                return $host[1];
            default:
                $tld = $host[1].'.'.$host[0];
                $suffixes = self::getSuffixes();

                if (in_array($tld, $suffixes, true)) {
                    return $host[2];
                }

                return $host[1];
        }
    }

    /**
     * @return string[]
     */
    private static function getSuffixes(): array
    {
        if (!isset(self::$suffixes)) {
            /** @var string[] */
            $suffixes = require dirname(__DIR__).'/resources/suffix.php';
            self::$suffixes = $suffixes;
        }

        return self::$suffixes;
    }
}
