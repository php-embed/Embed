<?php
declare(strict_types = 1);

namespace Embed\Http;

use Exception;
use Psr\Http\Client\RequestExceptionInterface;
use Psr\Http\Message\RequestInterface;

final class RequestException extends Exception implements RequestExceptionInterface
{
    private RequestInterface $request;

    public function __construct(string $message, int $code, RequestInterface $request)
    {
        parent::__construct($message, $code);
        $this->request = $request;
    }

    public function getRequest(): RequestInterface
    {
        return $this->request;
    }
}
