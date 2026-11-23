<?php
declare(strict_types = 1);

namespace Embed\Adapters\GoogleDrive;

use Embed\Extractor as Base;
use Psr\Http\Message\UriInterface;

/**
 * @template-extends Base<\Embed\Detectors\Detector<self>>
 */
class Extractor extends Base
{
    public function createCustomDetectors(): array
    {
        return [
            'code' => new Detectors\Code($this),
        ];
    }

    /**
     * Returns the file id of drive.google.com/file/d/<id>/view (also /edit,
     * /preview and /u/<n>/ variants) and drive.google.com/open?id=<id> URLs.
     */
    public static function parseFileId(UriInterface $uri): ?string
    {
        if (preg_match('#^/file(?:/u/\d+)?/d/([A-Za-z0-9_-]{10,})(?:/|$)#', $uri->getPath(), $matches) === 1) {
            return $matches[1];
        }

        if ($uri->getPath() !== '/open') {
            return null;
        }

        parse_str($uri->getQuery(), $query);
        $id = $query['id'] ?? null;

        if (is_string($id) && preg_match('/^[A-Za-z0-9_-]{10,}$/', $id) === 1) {
            return $id;
        }

        return null;
    }
}
