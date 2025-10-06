<?php
declare(strict_types = 1);

namespace Embed\Adapters\Github\Detectors;

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
        $path = $uri->getPath();

        if (!matchPath('/*/*/blob/*', $path)) {
            return null;
        }

        $dirs = explode('/', $path);

        $username = $dirs[1];
        $repo = $dirs[2];
        $ref = $dirs[4];
        $file = implode('/', array_slice($dirs, 5));
        $extension = pathinfo($file, PATHINFO_EXTENSION);

        switch ($extension) {
            case 'geojson':
                //https://help.github.com/articles/mapping-geojson-files-on-github/#embedding-your-map-elsewhere
                return new EmbedCode(html('script', ['src' => "https://embed.githubusercontent.com/view/geojson/{$username}/{$repo}/{$ref}/{$file}"]));
            case 'stl':
                //https://help.github.com/articles/3d-file-viewer/#embedding-your-model-elsewhere
                return new EmbedCode(html('script', ['src' => "https://embed.githubusercontent.com/view/3d/{$username}/{$repo}/{$ref}/{$file}"]));
        }

        return null;
    }
}
