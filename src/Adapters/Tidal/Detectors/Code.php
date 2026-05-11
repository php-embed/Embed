<?php
declare(strict_types = 1);

namespace Embed\Adapters\Tidal\Detectors;

use Embed\Detectors\Code as Detector;
use Embed\EmbedCode;
use function Embed\html;

class Code extends Detector
{
    public function detect(): ?EmbedCode
    {
        return parent::detect()
            ?: $this->fallback();
    }

    private function fallback(): ?EmbedCode
    {
        $uri = $this->extractor->getUri();

        if (!preg_match('{^/playlist/(?P<uuid>[0-9a-fA-F\-]{36})$}', $uri->getPath(), $matches)) {
            return NULL;
        }

        $html = html('iframe', [
            'src' => 'https://embed.tidal.com/playlists/' . $matches['uuid'] . '?disableAnalytics=true',
            'allow' => 'encrypted-media',
            'allowfullscreen' => 'allowfullscreen',
            'frameborder' => '0',
            'style' => 'width:100%;height:352px',
        ]);

        return new EmbedCode($html, null, 352);
    }
}
