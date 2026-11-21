<?php
declare(strict_types = 1);

namespace Embed\Adapters\GoogleDrive\Detectors;

use Embed\Adapters\GoogleDrive\Extractor;
use Embed\Detectors\Code as Detector;
use Embed\EmbedCode;
use function Embed\html;

/**
 * @extends Detector<\Embed\Adapters\GoogleDrive\Extractor>
 */
class Code extends Detector
{
    public function detect(): ?EmbedCode
    {
        $result = parent::detect();

        if ($result !== null) {
            return $result;
        }

        // Private or deleted files answer with an error status,
        // there is nothing to embed for them.
        if ($this->extractor->getResponse()->getStatusCode() >= 400) {
            return null;
        }

        $id = Extractor::parseFileId($this->extractor->getUri())
            ?? Extractor::parseFileId($this->extractor->getRequest()->getUri());

        if ($id === null) {
            return null;
        }

        // Same iframe as Drive's "Embed item" option.
        return new EmbedCode(html('iframe', [
            'src' => 'https://drive.google.com/file/d/'.rawurlencode($id).'/preview',
            'width' => 640,
            'height' => 480,
            'allow' => 'autoplay',
            'frameborder' => 0,
            'allowfullscreen' => 'true',
        ]), 640, 480);
    }
}
