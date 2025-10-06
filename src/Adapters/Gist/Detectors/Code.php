<?php
declare(strict_types = 1);

namespace Embed\Adapters\Gist\Detectors;

use Embed\Adapters\Gist\Extractor;
use Embed\Detectors\Code as Detector;
use Embed\EmbedCode;
use function Embed\html;

class Code extends Detector
{
    public function detect(): ?EmbedCode
    {
        $parentResult = parent::detect();
        return $parentResult !== null ? $parentResult : $this->fallback();
    }

    private function fallback(): ?EmbedCode
    {
        /** @var Extractor $extractor */
        $extractor = $this->extractor;
        $api = $extractor->getApi();

        $code = $api->html('div');
        $stylesheet = $api->str('stylesheet');

        if ($code !== null && $stylesheet !== null) {
            return new EmbedCode(
                html('link', ['rel' => 'stylesheet', 'href' => $stylesheet]).$code
            );
        }

        return null;
    }
}
