<?php
declare(strict_types = 1);

namespace Embed\Adapters\Gist\Detectors;

use Embed\Adapters\Gist\Extractor;
use Embed\Detectors\AuthorUrl as Detector;
use Psr\Http\Message\UriInterface;

class AuthorUrl extends Detector
{
    public function detect(): ?UriInterface
    {
        /** @var Extractor $extractor */
        $extractor = $this->extractor;
        $api = $extractor->getApi();
        $owner = $api->str('owner');

        // Exclude empty string and '0' to maintain original truthy check behavior
        // The string '0' is not a valid GitHub username and should not generate a URL
        if (is_string($owner) && $owner !== '' && $owner !== '0') {
            return $extractor->getCrawler()->createUri("https://github.com/{$owner}");
        }

        return parent::detect();
    }
}
