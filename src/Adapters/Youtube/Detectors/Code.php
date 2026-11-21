<?php
declare(strict_types = 1);

namespace Embed\Adapters\Youtube\Detectors;

use Embed\Detectors\Code as Detector;
use Embed\EmbedCode;

/**
 * @extends Detector<\Embed\Adapters\Youtube\Extractor>
 */
class Code extends Detector
{
    public function detect(): ?EmbedCode
    {
        $code = parent::detect();

        if ($code === null) {
            return null;
        }

        $times = array_filter([
            'start' => $this->detectTime('start', 't'),
            'end' => $this->detectTime('end'),
        ], static fn (?int $time): bool => $time !== null);

        if ($times === []) {
            return $code;
        }

        return self::withTimes($code, $times);
    }

    /**
     * YouTube's oEmbed only keeps the start time when it gets the exact URL,
     * and always drops the end time. Read both from the requested URL.
     */
    private function detectTime(string ...$names): ?int
    {
        foreach ([$this->extractor->getRequest()->getUri(), $this->extractor->getUri()] as $uri) {
            parse_str($uri->getQuery(), $query);

            foreach ($names as $name) {
                $value = $query[$name] ?? null;

                if (is_string($value)) {
                    $time = self::parseTime($value);

                    if ($time !== null) {
                        return $time;
                    }
                }
            }
        }

        return null;
    }

    /**
     * Accepts seconds (90) or YouTube's time format (1m30s, 1h2m3s).
     */
    public static function parseTime(string $time): ?int
    {
        if (preg_match('/^\d+$/', $time) === 1) {
            $seconds = (int) $time;
        } elseif (preg_match('/^(?:(\d+)h)?(?:(\d+)m)?(?:(\d+)s)?$/', $time, $matches) === 1 && $time !== '') {
            $seconds = ((int) ($matches[1] ?? 0)) * 3600 + ((int) ($matches[2] ?? 0)) * 60 + ((int) ($matches[3] ?? 0));
        } else {
            return null;
        }

        return $seconds > 0 ? $seconds : null;
    }

    /**
     * @param array<string, int> $times
     */
    private static function withTimes(EmbedCode $code, array $times): EmbedCode
    {
        $html = preg_replace_callback(
            '#(<iframe\b[^>]*?\bsrc=")(https://www\.youtube(?:-nocookie)?\.com/embed/[^"]*)(")#i',
            static function (array $matches) use ($times): string {
                $src = $matches[2];

                foreach ($times as $name => $value) {
                    if (preg_match('/[?&](?:amp;)?'.$name.'=/', $src) === 1) {
                        continue;
                    }

                    $src .= (strpos($src, '?') === false ? '?' : '&').$name.'='.$value;
                }

                return $matches[1].$src.$matches[3];
            },
            $code->html,
            1
        );

        if (!is_string($html) || $html === $code->html) {
            return $code;
        }

        return new EmbedCode($html, $code->width, $code->height);
    }
}
