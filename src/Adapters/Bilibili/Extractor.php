<?php
declare(strict_types = 1);

namespace Embed\Adapters\Bilibili;

use Embed\Extractor as Base;

/**
 * @template-extends Base<\Embed\Detectors\Detector<self>>
 */
class Extractor extends Base
{
    /**
     * Title of the page Bilibili serves when the video is not available,
     * for example when the request comes from a restricted region.
     */
    private const ERROR_TITLE = '出错啦';

    public function createCustomDetectors(): array
    {
        return [
            'code' => new Detectors\Code($this),
            'title' => new Detectors\Title($this),
            'description' => new Detectors\Description($this),
            'image' => new Detectors\Image($this),
        ];
    }

    public function isErrorPage(): bool
    {
        $title = $this->getDocument()->select('.//head/title')->str();

        return $title !== null && strpos($title, self::ERROR_TITLE) !== false;
    }

    /**
     * Returns the player query (bvid or aid) for a video path.
     *
     * @return array<string, string>|null
     */
    public static function parseVideoPath(string $path): ?array
    {
        if (preg_match('#^/video/(BV[0-9A-Za-z]{10})(?:/|$)#', $path, $matches) === 1) {
            return ['bvid' => $matches[1]];
        }

        if (preg_match('#^/video/av(\d+)(?:/|$)#i', $path, $matches) === 1) {
            return ['aid' => $matches[1]];
        }

        return null;
    }
}
