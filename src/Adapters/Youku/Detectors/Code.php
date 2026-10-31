<?php
declare(strict_types = 1);

namespace Embed\Adapters\Youku\Detectors;

use Embed\Adapters\Youku\Extractor;
use Embed\Detectors\Code as Detector;
use Embed\EmbedCode;
use function Embed\html;

/**
 * @extends Detector<\Embed\Adapters\Youku\Extractor>
 */
class Code extends Detector
{
    public function detect(): ?EmbedCode
    {
        $id = Extractor::parseVideoPath($this->extractor->getUri()->getPath());

        if ($id === null) {
            return parent::detect();
        }

        return new EmbedCode(html('iframe', [
            'src' => 'https://player.youku.com/embed/'.rawurlencode($id),
            'frameborder' => 0,
            'allowfullscreen' => 'true',
            'scrolling' => 'no',
            'width' => 640,
            'height' => 360,
        ]), 640, 360);
    }
}
