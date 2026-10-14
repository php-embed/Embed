<?php
declare(strict_types = 1);

namespace Embed\Tests;

use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

final class RecordingClient implements ClientInterface
{
    /** @var array<int, RequestInterface> */
    public array $requests = [];

    /** @var callable(RequestInterface): ResponseInterface */
    private $handler;

    /**
     * @param callable(RequestInterface): ResponseInterface $handler
     */
    public function __construct(callable $handler)
    {
        $this->handler = $handler;
    }

    public function sendRequest(RequestInterface $request): ResponseInterface
    {
        $this->requests[] = $request;
        $handler = $this->handler;

        return $handler($request);
    }
}
