<?php
declare(strict_types = 1);

namespace Embed\Adapters\Bilibili\Detectors;

use Embed\Adapters\Bilibili\Extractor;
use Embed\Detectors\Code as Detector;
use Embed\EmbedCode;
use function Embed\html;

/**
 * @extends Detector<\Embed\Adapters\Bilibili\Extractor>
 */
class Code extends Detector
{
    public function detect(): ?EmbedCode
    {
        $params = Extractor::parseVideoPath($this->extractor->getUri()->getPath());

        if ($params === null) {
            return parent::detect();
        }

        return new EmbedCode(html('iframe', [
            'src' => 'https://player.bilibili.com/player.html?'.http_build_query($params + ['autoplay' => 0]),
            'frameborder' => 0,
            'allowfullscreen' => 'true',
            'scrolling' => 'no',
            'width' => 640,
            'height' => 360,
        ]), 640, 360);
    }
}
