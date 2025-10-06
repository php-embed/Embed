<?php
declare(strict_types = 1);

namespace Embed;

use Exception;
use Psr\Http\Message\UriInterface;

trait HttpApiTrait
{
    use ApiTrait;

    private ?UriInterface $endpoint = null;

    public function getEndpoint(): ?UriInterface
    {
        return $this->endpoint;
    }

    /**
     * @return array<string, mixed>
     */
    private function fetchJSON(UriInterface $uri): array
    {
        $crawler = $this->extractor->getCrawler();
        $request = $crawler->createRequest('GET', $uri);
        $response = $crawler->sendRequest($request);

        try {
            $data = json_decode((string) $response->getBody(), true);
            if (is_array($data)) {
                /** @var array<string, mixed> */
                return $data;
            }
            return [];
        } catch (Exception $exception) {
            return [];
        }
    }
}
