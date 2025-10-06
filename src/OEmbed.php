<?php
declare(strict_types = 1);

namespace Embed;

use Exception;
use Psr\Http\Message\UriInterface;
use SimpleXMLElement;

class OEmbed
{
    use HttpApiTrait;

    /** @var array<mixed>|null */
    private static $providers = null;

    /** @var array<string, mixed> */
    private array $defaults = [];

    /**
     * @return array<mixed>
     */
    private static function getProviders(): array
    {
        if (self::$providers === null) {
            /** @var array<mixed> $loaded */
            $loaded = require __DIR__.'/resources/oembed.php';
            self::$providers = $loaded;
        }

        return self::$providers;
    }

    /**
     * @return array<string, mixed>
     */
    public function getOembedQueryParameters(string $url): array
    {
        $queryParameters = ['url' => $url, 'format' => 'json'];
        $setting = $this->extractor->getSetting('oembed:query_parameters');
        $additional = is_array($setting) ? $setting : [];

        /** @var array<string, mixed> $result */
        $result = array_merge($queryParameters, $additional);
        return $result;
    }

    /**
     * @return array<string, mixed>
     */
    protected function fetchData(): array
    {
        $this->endpoint = $this->detectEndpoint();

        if ($this->endpoint === null) {
            return [];
        }

        $crawler = $this->extractor->getCrawler();
        $request = $crawler->createRequest('GET', $this->endpoint);
        $response = $crawler->sendRequest($request);

        if (self::isXML($request->getUri())) {
            return $this->extractXML((string) $response->getBody());
        }

        return $this->extractJSON((string) $response->getBody());
    }

    protected function detectEndpoint(): ?UriInterface
    {
        $document = $this->extractor->getDocument();

        $endpoint = null;
        $types = [
            'application/json+oembed',
            'text/json+oembed',
            'application/xml+oembed',
            'text/xml+oembed',
        ];

        foreach ($types as $type) {
            $endpoint = $document->link('alternate', ['type' => $type]);
            if ($endpoint !== null) {
                break;
            }
        }

        if ($endpoint === null) {
            return $this->detectEndpointFromProviders();
        }

        // Add configured OEmbed query parameters
        parse_str($endpoint->getQuery(), $query);
        $setting = $this->extractor->getSetting('oembed:query_parameters');
        $additional = is_array($setting) ? $setting : [];
        $query = array_merge($query, $additional);
        $endpoint = $endpoint->withQuery(http_build_query($query));

        return $endpoint;
    }

    private function detectEndpointFromProviders(): ?UriInterface
    {
        $url = (string) $this->extractor->getUri();

        $endpoint = $this->detectEndpointFromUrl($url);
        if ($endpoint !== null) {
            return $endpoint;
        }

        $initialUrl = (string) $this->extractor->getRequest()->getUri();

        if ($initialUrl !== $url) {
            $endpoint = $this->detectEndpointFromUrl($initialUrl);
            if ($endpoint !== null) {
                $this->defaults['url'] = $initialUrl;
                return $endpoint;
            }
        }

        return null;
    }

    private function detectEndpointFromUrl(string $url): ?UriInterface
    {
        $endpoint = self::searchEndpoint(self::getProviders(), $url);

        if ($endpoint === null || $endpoint === '') {
            return null;
        }

        return $this->extractor->getCrawler()
            ->createUri($endpoint)
            ->withQuery(http_build_query($this->getOembedQueryParameters($url)));
    }

    /**
     * @param array<mixed> $providers
     */
    private static function searchEndpoint(array $providers, string $url): ?string
    {
        foreach ($providers as $endpoint => $patterns) {
            if (!is_array($patterns)) {
                continue;
            }
            foreach ($patterns as $pattern) {
                if (!is_string($pattern)) {
                    continue;
                }
                $matchResult = preg_match($pattern, $url);
                if ($matchResult === 1) {
                    return is_string($endpoint) ? $endpoint : null;
                }
            }
        }

        return null;
    }

    private static function isXML(UriInterface $uri): bool
    {
        $extension = pathinfo($uri->getPath(), PATHINFO_EXTENSION);

        if (strtolower($extension) === 'xml') {
            return true;
        }

        parse_str($uri->getQuery(), $params);
        $format = $params['format'] ?? null;

        if (is_string($format) && $format !== '' && strtolower($format) === 'xml') {
            return true;
        }

        return false;
    }

    /**
     * @return array<string, mixed>
     */
    private function extractXML(string $xml): array
    {
        try {
            // Remove the DOCTYPE declaration for to prevent XML Quadratic Blowup vulnerability
            $cleanedXml = preg_replace('/^<!DOCTYPE[^>]*+>/i', '', $xml, 1);
            if (!is_string($cleanedXml)) {
                return [];
            }
            $data = [];
            $errors = libxml_use_internal_errors(true);
            $content = new SimpleXMLElement($cleanedXml);
            libxml_use_internal_errors($errors);

            foreach ($content as $element) {
                $value = trim((string) $element);

                if (stripos($value, '<![CDATA[') === 0) {
                    $value = substr($value, 9, -3);
                }

                $name = $element->getName();
                $data[$name] = $value;
            }

            return $data !== [] ? ($data + $this->defaults) : [];
        } catch (Exception $exception) {
            return [];
        }
    }

    /**
     * @return array<string, mixed>
     */
    private function extractJSON(string $json): array
    {
        try {
            /** @var mixed $decoded */
            $decoded = json_decode($json, true);

            if (!is_array($decoded)) {
                return [];
            }

            /** @var array<string, mixed> $result */
            $result = $decoded + $this->defaults;
            return $result;
        } catch (Exception $exception) {
            return [];
        }
    }
}
