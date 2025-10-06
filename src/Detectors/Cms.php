<?php
declare(strict_types = 1);

namespace Embed\Detectors;

class Cms extends Detector
{
    public const BLOGSPOT = 'blogspot';
    public const WORDPRESS = 'wordpress';
    public const MEDIAWIKI = 'mediawiki';
    public const OPENNEMAS = 'opennemas';

    public function detect(): ?string
    {
        $cms = self::detectFromHost($this->extractor->getUri()->getHost());

        if ($cms !== null) {
            return $cms;
        }

        $document = $this->extractor->getDocument();
        $generators = $document->select('.//meta', ['name' => 'generator'])->strAll('content');

        foreach ($generators as $generator) {
            $cms = self::detectFromGenerator($generator);
            if ($cms !== null) {
                return $cms;
            }
        }

        return null;
    }

    private static function detectFromHost(string $host): ?string
    {
        if (strpos($host, '.blogspot.com') !== false) {
            return self::BLOGSPOT;
        }

        if (strpos($host, '.wordpress.com') !== false) {
            return self::WORDPRESS;
        }

        return null;
    }

    private static function detectFromGenerator(string $generator): ?string
    {
        $generator = strtolower($generator);

        if ($generator === 'blogger') {
            return self::BLOGSPOT;
        }

        if (strpos($generator, 'mediawiki') === 0) {
            return self::MEDIAWIKI;
        }

        if (strpos($generator, 'wordpress') === 0) {
            return self::WORDPRESS;
        }

        if (strpos($generator, 'opennemas') === 0) {
            return self::OPENNEMAS;
        }

        return null;
    }
}
