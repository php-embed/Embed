<?php
declare(strict_types = 1);

namespace Embed\Detectors;

use Psr\Http\Message\UriInterface;

class AuthorUrl extends Detector
{
    public function detect(): ?UriInterface
    {
        $oembed = $this->extractor->getOEmbed();

        $result = $oembed->url('author_url');
        return $result !== null ? $result : $this->detectFromTwitter();
    }

    private function detectFromTwitter(): ?UriInterface
    {
        $metas = $this->extractor->getMetas();
        $crawler = $this->extractor->getCrawler();

        $user = $metas->str('twitter:creator');

        return $user !== null
            ? $crawler->createUri(sprintf('https://twitter.com/%s', ltrim($user, '@')))
            : null;
    }
}
