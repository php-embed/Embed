<?php
declare(strict_types = 1);

namespace Embed\Http;

use InvalidArgumentException;
use Psr\Http\Client\RequestExceptionInterface;
use Psr\Http\Message\RequestInterface;

/**
 * The request was refused by the URL policy before a connection was used.
 *
 * The message is generic on purpose: it must not include a resolved address,
 * so a caller cannot use the failure as a DNS oracle.
 */
final class BlockedRequestException extends InvalidArgumentException implements RequestExceptionInterface
{
    private RequestInterface $request;

    /** @var 'scheme'|'host'|'non_public_address'|'port'|'redirect' */
    private string $reason;

    /**
     * @param 'scheme'|'host'|'non_public_address'|'port'|'redirect' $reason
     */
    public function __construct(string $message, RequestInterface $request, string $reason)
    {
        parent::__construct($message);
        $this->request = $request;
        $this->reason = $reason;
    }

    public function getRequest(): RequestInterface
    {
        return $this->request;
    }

    /**
     * @return 'scheme'|'host'|'non_public_address'|'port'|'redirect'
     */
    public function getReason(): string
    {
        return $this->reason;
    }
}
