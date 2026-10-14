<?php
declare(strict_types = 1);

namespace Embed\Tests;

/**
 * Answers schema.org JSON-LD context reads from inside the test process.
 *
 * ML\JsonLD\Document::load() is called without options, so
 * JsonLD::setDefaultDocumentLoader() is ignored and FileGetContentsLoader
 * downloads the @context. These page tests serve a minimal vocabulary
 * instead, which is enough to expand http://schema.org/ terms. Reads of
 * any other host fail, so a context URL cannot fall back to the network.
 */
final class SchemaOrgContext
{
    private const BODY = '{"@context":{"@vocab":"http://schema.org/"}}';

    /** @var resource|null */
    public $context;

    private string $body = '';
    private int $position = 0;

    public static function register(): void
    {
        foreach (['http', 'https'] as $scheme) {
            if (in_array($scheme, stream_get_wrappers(), true)) {
                stream_wrapper_unregister($scheme);
            }
            stream_wrapper_register($scheme, self::class);
        }
    }

    /**
     * @param string $path
     * @param string $mode
     * @param int $options
     * @param string|null $openedPath
     */
    public function stream_open($path, $mode, $options, &$openedPath): bool
    {
        if (!$this->isSchemaHost($path)) {
            return false;
        }

        $this->body = self::BODY;
        $this->position = 0;
        $this->notifyMimeType();

        return true;
    }

    /**
     * @param int $count
     */
    public function stream_read($count): string
    {
        $chunk = substr($this->body, $this->position, $count);
        $this->position += strlen($chunk);

        return $chunk;
    }

    public function stream_eof(): bool
    {
        return $this->position >= strlen($this->body);
    }

    /**
     * @return int
     */
    public function stream_tell()
    {
        return $this->position;
    }

    /**
     * @return array<int|string, int>
     */
    public function stream_stat(): array
    {
        $size = strlen($this->body);

        return [
            'size' => $size,
            7 => $size,
        ];
    }

    public function stream_close(): void
    {
        // The context body is a string, so there is no handle to close.
    }

    private function notifyMimeType(): void
    {
        if (!isset($this->context)) {
            return;
        }

        $params = stream_context_get_params($this->context);
        if (!isset($params['notification']) || !is_callable($params['notification'])) {
            return;
        }

        $params['notification'](
            STREAM_NOTIFY_MIME_TYPE_IS,
            STREAM_NOTIFY_SEVERITY_INFO,
            'application/ld+json',
            0,
            0,
            0
        );
    }

    /**
     * @param mixed $url
     */
    private function isSchemaHost($url): bool
    {
        if (!is_string($url) || $url === '') {
            return false;
        }

        $host = parse_url($url, PHP_URL_HOST);
        if (!is_string($host) || $host === '') {
            return false;
        }

        return in_array(strtolower($host), [
            'schema.org',
            'www.schema.org',
            'schema.googleapis.com',
        ], true);
    }
}
