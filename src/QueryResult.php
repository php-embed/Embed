<?php
declare(strict_types = 1);

namespace Embed;

use Closure;
use DOMElement;
use DOMNode;
use DOMNodeList;
use Psr\Http\Message\UriInterface;
use Throwable;

class QueryResult
{
    private Extractor $extractor;
    /** @var list<DOMNode> */
    private array $nodes = [];

    /**
     * @param DOMNodeList<DOMNode> $result
     */
    public function __construct(DOMNodeList $result, Extractor $extractor)
    {
        /** @var list<DOMNode> $nodeArray */
        $nodeArray = iterator_to_array($result, false);
        $this->nodes = $nodeArray;
        $this->extractor = $extractor;
    }

    public function node(): ?DOMElement
    {
        $firstNode = $this->nodes[0] ?? null;
        return $firstNode instanceof DOMElement ? $firstNode : null;
    }

    /**
     * @return list<DOMNode>
     */
    public function nodes(): array
    {
        return $this->nodes;
    }

    /**
     * @param Closure(DOMNode): bool $callback
     */
    public function filter(Closure $callback): self
    {
        $this->nodes = array_values(array_filter($this->nodes, $callback));

        return $this;
    }

    /**
     * @return mixed
     */
    public function get(?string $attribute = null)
    {
        $node = $this->node();

        if ($node === null) {
            return null;
        }

        return $attribute !== null ? self::getAttribute($node, $attribute) : $node->nodeValue;
    }

    /**
     * @return list<mixed>
     */
    public function getAll(?string $attribute = null): array
    {
        $nodes = $this->nodes();

        return array_values(array_filter(
            array_map(
                function(\DOMNode $node) use ($attribute) {
                    if (!$node instanceof DOMElement) {
                        return $attribute !== null ? null : $node->nodeValue;
                    }
                    return $attribute !== null ? self::getAttribute($node, $attribute) : $node->nodeValue;
                },
                $nodes
            ),
            fn($val) => $val !== null && $val !== ''
        ));
    }

    public function str(?string $attribute = null): ?string
    {
        $value = $this->get($attribute);

        if (!is_string($value) && !is_numeric($value)) {
            return null;
        }

        $cleaned = clean((string)$value);
        return $cleaned !== '' ? $cleaned : null;
    }

    /**
     * @return list<string>
     */
    public function strAll(?string $attribute = null): array
    {
        return array_values(array_filter(array_map(function($value) {
            if (!is_string($value) && !is_numeric($value)) {
                return null;
            }
            $cleaned = clean((string)$value);
            return $cleaned !== '' ? $cleaned : null;
        }, $this->getAll($attribute)), fn($v) => $v !== null));
    }

    public function int(?string $attribute = null): ?int
    {
        $value = $this->get($attribute);

        if ($value === null || $value === '' || $value === false) {
            return null;
        }

        return is_numeric($value) ? (int) $value : null;
    }

    public function url(?string $attribute = null): ?UriInterface
    {
        $value = $this->get($attribute);

        if (!is_string($value) || $value === '') {
            return null;
        }

        try {
            return $this->extractor->resolveUri($value);
        } catch (Throwable $error) {
            return null;
        }
    }

    private static function getAttribute(DOMElement $node, string $name): ?string
    {
        //Don't use $node->getAttribute() because it does not work with namespaces (ex: xml:lang)
        $attributes = $node->attributes;

        for ($i = 0; $i < $attributes->length; ++$i) {
            $attribute = $attributes->item($i);

            if ($attribute !== null && $attribute->name === $name) {
                return $attribute->nodeValue;
            }
        }

        return null;
    }
}
