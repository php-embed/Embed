<?php
declare(strict_types = 1);

namespace Embed\Http;

use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * Class to fetch html pages
 */
final class CurlClient implements ClientInterface
{
    private ResponseFactoryInterface $responseFactory;
    /** @var array<string, mixed> */
    private array $settings = [];

    public function __construct(?ResponseFactoryInterface $responseFactory = null)
    {
        $this->responseFactory = $responseFactory !== null ? $responseFactory : FactoryDiscovery::getResponseFactory();
    }

    /**
     * @param array<string, mixed> $settings
     */
    public function setSettings(array $settings): void
    {
        $this->settings = $settings + $this->settings;
    }

    public function sendRequest(RequestInterface $request): ResponseInterface
    {
        $responses = CurlDispatcher::fetch($this->settings, $this->responseFactory, $request);

        return $responses[0];
    }

    /**
     * @return ResponseInterface[]
     */
    public function sendRequests(RequestInterface ...$request): array
    {
        return CurlDispatcher::fetch($this->settings, $this->responseFactory, ...$request);
    }
}
