<?php
declare(strict_types = 1);

namespace Embed;

use Embed\Http\Crawler;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class Embed
{
    private Crawler $crawler;
    private ExtractorFactory $extractorFactory;

    public function __construct(?Crawler $crawler = null, ?ExtractorFactory $extractorFactory = null)
    {
        $this->crawler = $crawler !== null ? $crawler : new Crawler();
        $this->extractorFactory = $extractorFactory !== null ? $extractorFactory : new ExtractorFactory();
    }

    public function get(string $url): Extractor
    {
        $request = $this->crawler->createRequest('GET', $url);
        $response = $this->crawler->sendRequest($request);

        return $this->extract($request, $response);
    }

    /**
     * @return Extractor[]
     */
    public function getMulti(string ...$urls): array
    {
        $requests = array_map(
            fn ($url) => $this->crawler->createRequest('GET', $url),
            $urls
        );

        $responses = $this->crawler->sendRequests(...$requests);

        $return = [];

        foreach ($responses as $k => $response) {
            /** @phpstan-ignore instanceof.alwaysTrue (defensive check for error handling) */
            if ($response instanceof ResponseInterface) {
                $return[] = $this->extract($requests[$k], $response);
            }
        }

        return $return;
    }

    public function getCrawler(): Crawler
    {
        return $this->crawler;
    }

    public function getExtractorFactory(): ExtractorFactory
    {
        return $this->extractorFactory;
    }

    /**
     * @param array<string, mixed> $settings
     */
    public function setSettings(array $settings): void
    {
        $this->extractorFactory->setSettings($settings);
    }

    private function extract(RequestInterface $request, ResponseInterface $response, bool $redirect = true): Extractor
    {
        $uri = $this->crawler->getResponseUri($response);
        if ($uri === null) {
            $uri = $request->getUri();
        }

        $extractor = $this->extractorFactory->createExtractor($uri, $request, $response, $this->crawler);

        if (!$redirect || !$this->mustRedirect($extractor)) {
            return $extractor;
        }

        // Magic property access returns mixed, but we know it's ?UriInterface from Redirect detector
        $redirectUri = $extractor->redirect;
        if (!($redirectUri instanceof \Psr\Http\Message\UriInterface)) {
            return $extractor;
        }

        $request = $this->crawler->createRequest('GET', (string) $redirectUri);
        $response = $this->crawler->sendRequest($request);

        return $this->extract($request, $response, false);
    }

    private function mustRedirect(Extractor $extractor): bool
    {
        if ($extractor->getOEmbed()->all() !== []) {
            return false;
        }

        // Magic property access returns mixed, but we know it's ?UriInterface from Redirect detector
        $redirectUri = $extractor->redirect;
        return $redirectUri instanceof \Psr\Http\Message\UriInterface;
    }
}
