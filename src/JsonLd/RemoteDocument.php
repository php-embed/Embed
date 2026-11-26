<?php
declare(strict_types = 1);

namespace Embed\JsonLd;

/**
 * Document returned by {@see DocumentLoader}.
 */
final class RemoteDocument
{
    public string $documentUrl;

    /** @var mixed */
    public $document;

    /**
     * @param mixed $document
     */
    public function __construct(string $documentUrl, $document)
    {
        $this->documentUrl = $documentUrl;
        $this->document = $document;
    }
}
