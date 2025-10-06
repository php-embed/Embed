<?php
declare(strict_types = 1);

namespace Embed;

use DOMDocument;
use DOMNode;
use DOMXPath;
use HtmlParser\Parser;
use Psr\Http\Message\UriInterface;
use RuntimeException;
use Symfony\Component\CssSelector\CssSelectorConverter;

class Document
{
    private static CssSelectorConverter $cssConverter;
    private Extractor $extractor;
    private DOMDocument $document;
    private DOMXPath $xpath;

    public function __construct(Extractor $extractor)
    {
        $this->extractor = $extractor;

        $html = (string) $extractor->getResponse()->getBody();
        $html = str_replace('<br>', "\n<br>", $html);
        $html = str_replace('<br ', "\n<br ", $html);

        $encoding = null;
        $contentType = $extractor->getResponse()->getHeaderLine('content-type');
        preg_match('/charset=(?:"|\')?(.*?)(?=$|\s|;|"|\'|>)/i', $contentType, $match);
        if (isset($match[1]) && $match[1] !== '' && $match[1] !== '0') {
            $encoding = trim($match[1], ',');
            $encoding = $this->getValidEncoding($encoding);
        }
        if (is_null($encoding) && $html !== '') {
            preg_match('/charset=(?:"|\')?(.*?)(?=$|\s|;|"|\'|>)/i', $html, $match);
            if (isset($match[1]) && $match[1] !== '' && $match[1] !== '0') {
                $encoding = trim($match[1], ',');
                $encoding = $this->getValidEncoding($encoding);
            }
        }
        $this->document = $html !== '' ? Parser::parse($html, $encoding) : new DOMDocument();
        $this->initXPath();
    }

    /**
     * Get valid encoding name if it exists, otherwise return null
     *
     * Uses mb_encoding_aliases() to verify the encoding is valid.
     *
     * TODO: When dropping PHP 7.4 support, remove the PHP_VERSION_ID < 80000 branch.
     * PHP version differences:
     * - PHP 7.4: mb_encoding_aliases() returns false for invalid encoding and throws Warning for empty string
     * - PHP 8.0+: mb_encoding_aliases() throws ValueError for invalid/empty encoding
     *
     * @see https://www.php.net/manual/en/function.mb-encoding-aliases.php
     */
    private function getValidEncoding(?string $encoding): ?string
    {
        if (PHP_VERSION_ID < 80000) {
            // PHP 7.4: Check return value (false = invalid encoding)
            // Need to check null/empty first to avoid Warning
            // TODO: Remove this entire branch when PHP 7.4 support is dropped
            if ($encoding === null || $encoding === '') {
                return null;
            }
            $ret = @mb_encoding_aliases($encoding);
            /** @phpstan-ignore function.alreadyNarrowedType (PHP 7.4 returns false for invalid encoding, PHP 8.0+ returns array) */
            return is_array($ret) ? $encoding : null;
        } else {
            // PHP 8.0+: ValueError exception is thrown for invalid/empty encoding
            try {
                $aliases = mb_encoding_aliases($encoding ?? '');
                // If mb_encoding_aliases succeeds, return the input value as is. Some encodings do not have aliases.
                return $encoding;
            } catch (\ValueError $exception) {
                return null;
            }
        }
    }

    private function initXPath(): void
    {
        $this->xpath = new DOMXPath($this->document);
        $this->xpath->registerNamespace('php', 'http://php.net/xpath');
        $this->xpath->registerPhpFunctions();
    }

    public function __clone()
    {
        $this->document = clone $this->document;
        $this->initXPath();
    }

    public function remove(string $query): void
    {
        $result = $this->xpath->query($query);
        if ($result === false) {
            return;
        }
        $nodes = iterator_to_array($result, false);

        foreach ($nodes as $node) {
            if ($node->parentNode !== null) {
                $node->parentNode->removeChild($node);
            }
        }
    }

    public function removeCss(string $query): void
    {
        $this->remove(self::cssToXpath($query));
    }

    public function getDocument(): DOMDocument
    {
        return $this->document;
    }

    /**
     * Helper to build xpath queries easily and case insensitive
     *
     * @param array<string, string> $attributes
     */
    private static function buildQuery(string $startQuery, array $attributes): string
    {
        $selector = [$startQuery];

        foreach ($attributes as $name => $value) {
            $selector[] = sprintf('[php:functionString("strtolower", @%s)="%s"]', $name, mb_strtolower($value));
        }

        return implode('', $selector);
    }

    /**
     * Select a element in the dom
     *
     * @param array<string, string>|null $attributes
     */
    public function select(string $query, ?array $attributes = null, ?DOMNode $context = null): QueryResult
    {
        if ($attributes !== null && $attributes !== []) {
            $query = self::buildQuery($query, $attributes);
        }

        $result = $this->xpath->query($query, $context);
        if ($result === false) {
            $result = new \DOMNodeList();
        }
        return new QueryResult($result, $this->extractor);
    }

    /**
     * Select a element in the dom using a css selector
     */
    public function selectCss(string $query, ?DOMNode $context = null): QueryResult
    {
        return $this->select(self::cssToXpath($query), null, $context);
    }

    /**
     * Shortcut to select a <link> element and return the href
     *
     * @param array<string, string> $extra
     */
    public function link(string $rel, array $extra = []): ?UriInterface
    {
        return $this->select('.//link', ['rel' => $rel] + $extra)->url('href');
    }

    public function __toString(): string
    {
        return Parser::stringify($this->getDocument());
    }

    private static function cssToXpath(string $selector): string
    {
        if (!isset(self::$cssConverter)) {
            if (!class_exists(CssSelectorConverter::class)) {
                throw new RuntimeException('You need to install "symfony/css-selector" to use css selectors');
            }

            self::$cssConverter = new CssSelectorConverter();
        }

        return self::$cssConverter->toXPath($selector);
    }
}
