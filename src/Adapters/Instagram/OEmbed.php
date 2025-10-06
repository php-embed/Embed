<?php
declare(strict_types = 1);

namespace Embed\Adapters\Instagram;

use Embed\OEmbed as Base;
use Psr\Http\Message\UriInterface;

class OEmbed extends Base
{
    const ENDPOINT = 'https://graph.facebook.com/v8.0/instagram_oembed';

    protected function detectEndpoint(): ?UriInterface
    {
        $token = $this->extractor->getSetting('instagram:token');

        if (!is_string($token) || $token === '') {
            return null;
        }

        $uri = $this->extractor->getUri();
        if (strpos($uri->getPath(), 'login') !== false) {
            $uri = $this->extractor->getRequest()->getUri();
        }

        $queryParameters = $this->getOembedQueryParameters((string) $uri);
        $queryParameters['access_token'] = $token;

        return $this->extractor->getCrawler()
            ->createUri(self::ENDPOINT)
            ->withQuery(http_build_query($queryParameters));
    }
}
