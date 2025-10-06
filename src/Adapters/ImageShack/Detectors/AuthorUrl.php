<?php
declare(strict_types = 1);

namespace Embed\Adapters\ImageShack\Detectors;

use Embed\Adapters\ImageShack\Extractor;
use Embed\Detectors\AuthorUrl as Detector;
use Psr\Http\Message\UriInterface;

class AuthorUrl extends Detector
{
    public function detect(): ?UriInterface
    {
        /** @var Extractor $extractor */
        $extractor = $this->extractor;
        $api = $extractor->getApi();
        $owner = $api->str('owner', 'username');

        // Exclude empty string and '0' to maintain original truthy check behavior
        // The string '0' is not a valid username and should not generate a URL
        if (is_string($owner) && $owner !== '' && $owner !== '0') {
            return $extractor->getCrawler()->createUri("https://imageshack.com/{$owner}");
        }

        return parent::detect();
    }
}
