<?php
declare(strict_types = 1);

namespace Embed\Detectors;

use Psr\Http\Message\UriInterface;

class Redirect extends Detector
{
    public function detect(): ?UriInterface
    {
        $document = $this->extractor->getDocument();
        $value = $document->select('.//meta', ['http-equiv' => 'refresh'])->str('content');

        return $value !== null ? $this->extract($value) : null;
    }

    private function extract(string $value): ?UriInterface
    {
        if (preg_match('/url=(.+)$/i', $value, $match) === 1) {
            return $this->extractor->resolveUri(trim($match[1], '\'"'));
        }

        return null;
    }
}
