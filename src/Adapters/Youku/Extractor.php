<?php
declare(strict_types = 1);

namespace Embed\Adapters\Youku;

use Embed\Extractor as Base;

/**
 * @template-extends Base<\Embed\Detectors\Detector<self>>
 */
class Extractor extends Base
{
    public function createCustomDetectors(): array
    {
        return [
            'code' => new Detectors\Code($this),
        ];
    }

    public static function parseVideoPath(string $path): ?string
    {
        if (preg_match('#^/v_show/id_([0-9A-Za-z=]+)\.html$#', $path, $matches) === 1) {
            return $matches[1];
        }

        return null;
    }
}
