<?php
declare(strict_types = 1);

namespace Embed\Adapters\Snipplr\Detectors;

use Embed\Detectors\Code as Detector;
use Embed\EmbedCode;
use function Embed\html;
use function Embed\matchPath;

class Code extends Detector
{
    public function detect(): ?EmbedCode
    {
        $result = parent::detect();
        return $result !== null ? $result : $this->fallback();
    }

    private function fallback(): ?EmbedCode
    {
        $uri = $this->extractor->getUri();

        if (!matchPath('/view/*', $uri->getPath())) {
            return null;
        }

        $id = explode('/', $uri->getPath())[2];

        $html = [
            html('div', [
                'id' => "snipplr_embed_{$id}",
                'class' => 'snipplr_embed',
            ], '<a target="blank" href="https://snipplr.com/view/'.$id.'">View this snippet</a> on Snipplr'),
            html('script', [
                'type' => 'text/javascript',
                'src' => 'https://snipplr.com/js/embed.js',
            ]),
            html('script', [
                'type' => 'text/javascript',
                'src' => "https://snipplr.com/json/{$id}",
            ]),
        ];

        return new EmbedCode(implode('', $html));
    }
}
