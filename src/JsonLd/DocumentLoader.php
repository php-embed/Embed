<?php
declare(strict_types = 1);

namespace Embed\JsonLd;

use ML\JsonLD\DocumentLoaderInterface;
use ML\JsonLD\Exception\JsonLdException;
use ML\JsonLD\Processor;
use ML\JsonLD\RemoteDocument;

/**
 * JSON-LD document loader that never touches the network or the filesystem.
 *
 * LinkedData passes this as the documentLoader option. The global
 * JsonLD::setDefaultDocumentLoader() is not used, so a host application
 * keeps its own loader.
 *
 * schema.org context URLs are answered with a bundled vocabulary of
 * {"@context":{"@vocab":"http://schema.org/"}}. Every other remote URL or
 * path raises JsonLdException, which LinkedData already catches.
 */
final class DocumentLoader implements DocumentLoaderInterface
{
    private const SCHEMA_CONTEXT = '{"@context":{"@vocab":"http://schema.org/"}}';

    /** @var array<int, string> */
    private const SCHEMA_HOSTS = [
        'schema.org',
        'www.schema.org',
        'schema.googleapis.com',
    ];

    /**
     * @param string $url
     *
     * @throws JsonLdException
     *
     * @return RemoteDocument
     */
    public function loadDocument($url)
    {
        $trimmed = trim($url);
        if (isset($trimmed[0]) && ($trimmed[0] === '{' || $trimmed[0] === '[')) {
            return new RemoteDocument($url, Processor::parse($trimmed));
        }

        if ($this->isSchemaOrgContext($trimmed)) {
            return new RemoteDocument($url, Processor::parse(self::SCHEMA_CONTEXT), 'application/ld+json');
        }

        throw new JsonLdException(
            JsonLdException::LOADING_REMOTE_CONTEXT_FAILED,
            'Remote JSON-LD documents are not loaded.'
        );
    }

    private function isSchemaOrgContext(string $url): bool
    {
        $parts = parse_url($url);
        if (!is_array($parts)) {
            return false;
        }

        $scheme = $parts['scheme'] ?? null;
        if (!is_string($scheme)) {
            return false;
        }
        $scheme = strtolower($scheme);
        if ($scheme !== 'http' && $scheme !== 'https') {
            return false;
        }

        if (isset($parts['user']) || isset($parts['pass'])) {
            return false;
        }

        if (isset($parts['port'])) {
            $port = $parts['port'];
            $defaultPort = $scheme === 'https' ? 443 : 80;
            if ($port !== $defaultPort) {
                return false;
            }
        }

        $host = $parts['host'] ?? null;
        if (!is_string($host) || !in_array(strtolower($host), self::SCHEMA_HOSTS, true)) {
            return false;
        }

        return $this->isSchemaContextPath($parts['path'] ?? '');
    }

    private function isSchemaContextPath(string $path): bool
    {
        $normalized = strtolower(rtrim($path, '/'));
        if ($this->isBundledContextPath($normalized)) {
            return true;
        }

        $suffix = substr($normalized, -7);
        if ($suffix !== '.jsonld') {
            return false;
        }

        $withoutSuffix = substr($normalized, 0, -7);
        if ($withoutSuffix === false) {
            return false;
        }

        return $this->isBundledContextPath(rtrim($withoutSuffix, '/'));
    }

    private function isBundledContextPath(string $path): bool
    {
        return $path === ''
            || $path === '/docs/jsonldcontext.json'
            || $path === '/docs/jsonldcontext.jsonld';
    }
}
