<?php
declare(strict_types = 1);

namespace Embed\Adapters\Twitter;

use Embed\Extractor as Base;
use Embed\Http\Crawler;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\UriInterface;

/**
 * @template-extends Base<\Embed\Detectors\Detector<self>>
 */
class Extractor extends Base
{
    private Api $api;

    public function __construct(
        UriInterface $uri,
        RequestInterface $request,
        ResponseInterface $response,
        Crawler $crawler
    ) {
        parent::__construct($uri, $request, $response, $crawler);
        $this->api = new Api($this);
        $this->oembed = new OEmbed($this);
    }

    /**
     * The status URL of the tweet, taken from the requested URL first because
     * the final URL can be a login or migration page after the redirects.
     */
    public function getStatusUri(): ?UriInterface
    {
        foreach ([$this->getRequest()->getUri(), $this->getUri()] as $uri) {
            $host = strtolower($uri->getHost());
            if (preg_match('/^(?:(?:www|mobile)\\.)?(twitter|x)\\.com$/', $host, $site) !== 1) {
                continue;
            }
            if (preg_match('#^/([^/]+)/status(?:es)?/(\\d+)#', $uri->getPath(), $match) === 1) {
                return $this->getCrawler()->createUri("https://{$site[1]}.com/{$match[1]}/status/{$match[2]}");
            }
        }

        return null;
    }

    public function getApi(): Api
    {
        return $this->api;
    }

    public function createCustomDetectors(): array
    {
        return [
            'authorName' => new Detectors\AuthorName($this),
            'authorUrl' => new Detectors\AuthorUrl($this),
            'description' => new Detectors\Description($this),
            'image' => new Detectors\Image($this),
            'providerName' => new Detectors\ProviderName($this),
            'publishedTime' => new Detectors\PublishedTime($this),
            'title' => new Detectors\Title($this),
        ];
    }
}
