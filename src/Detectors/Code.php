<?php
declare(strict_types = 1);

namespace Embed\Detectors;

use Embed\EmbedCode;
use function Embed\html;

/**
 * @template TExtractor of \Embed\Extractor
 * @template-extends Detector<TExtractor>
 */
class Code extends Detector
{
    private const HLS_TYPES = ['application/x-mpegurl', 'application/vnd.apple.mpegurl'];

    public function detect(): ?EmbedCode
    {
        $result = $this->detectFromEmbed();
        if ($result !== null) {
            return $result;
        }

        $result = $this->detectFromOpenGraph();
        if ($result !== null) {
            return $result;
        }

        $result = $this->detectFromTwitter();
        if ($result !== null) {
            return $result;
        }

        return $this->detectFromContentType();
    }

    private function detectFromEmbed(): ?EmbedCode
    {
        $oembed = $this->extractor->getOEmbed();
        $html = $oembed->html('html');

        if ($html === null) {
            return $this->detectFromOEmbedPhoto();
        }

        return new EmbedCode(
            $html,
            $oembed->int('width'),
            $oembed->int('height')
        );
    }

    private function detectFromOEmbedPhoto(): ?EmbedCode
    {
        $oembed = $this->extractor->getOEmbed();

        if ($oembed->str('type') !== 'photo') {
            return null;
        }

        $url = $oembed->url('url');

        if ($url === null) {
            return null;
        }

        $width = $oembed->int('width');
        $height = $oembed->int('height');

        $code = html('img', [
            'src' => $url,
            'alt' => $oembed->str('title'),
            'width' => $width,
            'height' => $height,
        ]);

        return new EmbedCode($code, $width, $height);
    }

    private function detectFromOpenGraph(): ?EmbedCode
    {
        $metas = $this->extractor->getMetas();

        $url = $metas->url('og:video:secure_url', 'og:video:url', 'og:video');

        if ($url === null) {
            return null;
        }

        $type = pathinfo($url->getPath(), PATHINFO_EXTENSION);
        if ($type === '') {
            $type = $metas->str('og:video_type');
        }

        // HLS playlists cannot be used as an iframe src. og:video:type is only
        // read for this check, so existing pages keep their current code.
        $declaredType = strtolower((string) $metas->str('og:video:type'));
        if (strtolower($type ?? '') === 'm3u8' || in_array($declaredType, self::HLS_TYPES, true)) {
            return null;
        }

        $width = $metas->int('twitter:player:width');
        $height = $metas->int('twitter:player:height');

        switch ($type !== null ? strtolower($type) : null) {
            case 'swf':
            case 'application/x-shockwave-flash':
                return null; //Ignore flash
            case 'application/x-mpegurl':
            case 'application/vnd.apple.mpegurl':
                return null; //HLS playlists cannot be used as an iframe src
            case 'mp4':
            case 'ogg':
            case 'ogv':
            case 'webm':
            case 'application/mp4':
            case 'video/mp4':
            case 'video/ogg':
            case 'video/ogv':
            case 'video/webm':
                $code = html('video', [
                    'src' => $url,
                    'width' => $width,
                    'height' => $height,
                ]);
                break;
            default:
                $code = html('iframe', [
                    'src' => $url,
                    'frameborder' => 0,
                    'width' => $width,
                    'height' => $height,
                    'allowTransparency' => 'true',
                ]);
        }

        return new EmbedCode($code, $width, $height);
    }

    private function detectFromTwitter(): ?EmbedCode
    {
        $metas = $this->extractor->getMetas();

        $url = $metas->url('twitter:player');

        if ($url === null) {
            return null;
        }

        $width = $metas->int('twitter:player:width');
        $height = $metas->int('twitter:player:height');

        $code = html('iframe', [
            'src' => $url,
            'frameborder' => 0,
            'width' => $width,
            'height' => $height,
            'allowTransparency' => 'true',
        ]);

        return new EmbedCode($code, $width, $height);
    }

    private function detectFromContentType(): ?EmbedCode
    {
        if (!$this->extractor->getResponse()->hasHeader('content-type')) {
            return null;
        }

        $contentType = $this->extractor->getResponse()->getHeader('content-type')[0];
        $isBinary = preg_match('/(text|html|json)/', strtolower($contentType)) !== 1;
        if (!$isBinary) {
            return null;
        }

        $url = $this->extractor->getRequest()->getUri();

        if (strpos($contentType, 'video/') === 0 || $contentType === 'application/mp4') {
            $code = html('video', [
                'src' => $url,
                'controls' => true,
            ]);
        } elseif (strpos($contentType, 'audio/') === 0) {
            $code = html('audio', [
                'src' => $url,
                'controls' => true,
            ]);
        } elseif (strpos($contentType, 'image/') === 0) {
            $code = html('img', [
                'src' => $url,
            ]);
        } else {
            return null;
        }

        return new EmbedCode($code);
    }
}
