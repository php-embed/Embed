<?php
declare(strict_types = 1);

namespace Embed\Adapters\TencentVideo\Detectors;

use Embed\Adapters\TencentVideo\Extractor;
use Embed\Detectors\Code as Detector;
use Embed\EmbedCode;
use function Embed\html;

/**
 * @extends Detector<\Embed\Adapters\TencentVideo\Extractor>
 */
class Code extends Detector
{
    public function detect(): ?EmbedCode
    {
        $vid = Extractor::parseVideoId($this->extractor->getUri());

        if ($vid === null) {
            return parent::detect();
        }

        // Same player as Tencent Video's share code.
        return new EmbedCode(html('iframe', [
            'src' => 'https://v.qq.com/txp/iframe/player.html?vid='.rawurlencode($vid),
            'frameborder' => 0,
            'allowfullscreen' => 'true',
            'scrolling' => 'no',
            'width' => 640,
            'height' => 360,
        ]), 640, 360);
    }
}
