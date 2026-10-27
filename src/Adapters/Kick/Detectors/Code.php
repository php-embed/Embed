<?php
declare(strict_types = 1);

namespace Embed\Adapters\Kick\Detectors;

use Embed\Adapters\Kick\Extractor;
use Embed\Detectors\Code as Detector;
use Embed\EmbedCode;
use function Embed\html;

/**
 * @extends Detector<\Embed\Adapters\Kick\Extractor>
 */
class Code extends Detector
{
    public function detect(): ?EmbedCode
    {
        $path = $this->extractor->getUri()->getPath();
        $channel = Extractor::parseChannelPath($path);

        if ($channel !== null) {
            return new EmbedCode(html('iframe', [
                'src' => 'https://player.kick.com/'.rawurlencode($channel),
                'frameborder' => 0,
                'allowfullscreen' => 'true',
                'scrolling' => 'no',
                'width' => 640,
                'height' => 360,
            ]), 640, 360);
        }

        // Kick has no documented player URL for VODs and clips, and their
        // og:video is an HLS playlist that cannot be used as an iframe.
        return null;
    }
}
