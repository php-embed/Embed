<?php
declare(strict_types = 1);

namespace Embed\Adapters\NetEase\Detectors;

use Embed\Adapters\NetEase\Extractor;
use Embed\Detectors\Code as Detector;
use Embed\EmbedCode;
use function Embed\html;

/**
 * @extends Detector<\Embed\Adapters\NetEase\Extractor>
 */
class Code extends Detector
{
    public function detect(): ?EmbedCode
    {
        $item = Extractor::parseUri($this->extractor->getUri());

        if ($item === null) {
            return parent::detect();
        }

        $query = http_build_query([
            'type' => $item[0],
            'id' => $item[1],
            'auto' => 0,
            'height' => 66,
        ]);

        return new EmbedCode(html('iframe', [
            'src' => "https://music.163.com/outchain/player?{$query}",
            'frameborder' => 0,
            'scrolling' => 'no',
            'width' => 330,
            'height' => 86,
        ]), 330, 86);
    }
}
