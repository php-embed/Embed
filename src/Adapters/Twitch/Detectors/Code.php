<?php
declare(strict_types = 1);

namespace Embed\Adapters\Twitch\Detectors;

use Embed\Detectors\Code as Detector;
use Embed\EmbedCode;
use function Embed\html;

/**
 * @extends Detector<\Embed\Adapters\Twitch\Extractor>
 */
class Code extends Detector
{
    public function detect(): ?EmbedCode
    {
        $result = parent::detect();
        return $result !== null ? $result : $this->fallback();
    }

    private function fallback(): ?EmbedCode
    {
        $path = $this->extractor->getUri()->getPath();
        $parent = $this->extractor->getSetting('twitch:parent');

        $id = self::getVideoId($path);
        if ($id !== null) {
            $code = $parent !== null
                ? self::generateIframeCode(['video' => "v{$id}", 'parent' => $parent])
                : self::generateJsCode('video', $id);
            return new EmbedCode($code, 620, 378);
        }

        $id = self::getClipId($this->extractor->getUri()->getHost(), $path);
        if ($id !== null) {
            if ($parent === null) {
                // Clips only have an iframe player, and it requires a parent.
                return null;
            }
            $query = http_build_query(['clip' => $id, 'parent' => $parent, 'autoplay' => 'false']);
            $code = html('iframe', [
                'src' => "https://clips.twitch.tv/embed?{$query}",
                'frameborder' => 0,
                'allowfullscreen' => 'true',
                'scrolling' => 'no',
                'height' => 378,
                'width' => 620,
            ]);
            return new EmbedCode($code, 620, 378);
        }

        $id = self::getChannelId($path);
        if ($id !== null) {
            $code = $parent !== null
                ? self::generateIframeCode(['channel' => $id, 'parent' => $parent])
                : self::generateJsCode('channel', $id);
            return new EmbedCode($code, 620, 378);
        }

        return null;
    }

    private static function getVideoId(string $path): ?string
    {
        if (preg_match('#^/videos/(\d+)$#', $path, $matches) === 1) {
            return $matches[1];
        }

        return null;
    }

    private static function getClipId(string $host, string $path): ?string
    {
        if (preg_match('#^/\w+/clip/([\w-]+)$#', $path, $matches) === 1) {
            return $matches[1];
        }

        if ($host === 'clips.twitch.tv' && preg_match('#^/([\w-]+)$#', $path, $matches) === 1 && $matches[1] !== 'embed') {
            return $matches[1];
        }

        return null;
    }

    private static function getChannelId(string $path): ?string
    {
        if (preg_match('#^/(\w+)$#', $path, $matches) === 1) {
            return $matches[1];
        }

        return null;
    }

    /**
     * @param array<string, mixed> $params
     */
    private static function generateIframeCode(array $params): string
    {
        $query = http_build_query(['autoplay' => 'false'] + $params);

        return html('iframe', [
            'src' => "https://player.twitch.tv/?{$query}",
            'frameborder' => 0,
            'allowfullscreen' => 'true',
            'scrolling' => 'no',
            'height' => 378,
            'width' => 620,
        ]);
    }

    private static function generateJsCode(string $key, string $value): string
    {
        return <<<HTML
        <div id="twitch-embed"></div>
        <script src="https://player.twitch.tv/js/embed/v1.js"></script>
        <script type="text/javascript">
            new Twitch.Player("twitch-embed", { {$key}: "{$value}" });
        </script>
        HTML;
    }
}
