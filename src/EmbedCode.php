<?php
declare(strict_types = 1);

namespace Embed;

use JsonSerializable;
use ReturnTypeWillChange;

class EmbedCode implements JsonSerializable
{
    public string $html;
    public ?int $width;
    public ?int $height;
    public ?float $ratio = null;

    public function __construct(string $html, ?int $width = null, ?int $height = null)
    {
        $this->html = $html;
        $this->width = $width;
        $this->height = $height;

        if ($width !== null && $width !== 0 && $height !== null && $height !== 0) {
            $this->ratio = round(($height / $width) * 100, 3);
        }
    }

    public function __toString(): string
    {
        return $this->html;
    }

    #[ReturnTypeWillChange]
    public function jsonSerialize()
    {
        return [
            'html' => $this->html,
            'width' => $this->width,
            'height' => $this->height,
            'ratio' => $this->ratio,
        ];
    }
}
