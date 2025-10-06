<?php
declare(strict_types = 1);

namespace Embed;

use Exception;
use ML\JsonLD\JsonLD;
use ML\JsonLD\Document as LdDocument;
use ML\JsonLD\DocumentInterface;
use ML\JsonLD\GraphInterface;
use ML\JsonLD\Node;
use Throwable;

class LinkedData
{
    use ApiTrait;

    private ?DocumentInterface $document = null;

    /** @var array<string, mixed> */
    private array $allData = [];

    /**
     * @return mixed
     */
    public function get(string ...$keys)
    {
        $graph = $this->getGraph();

        if ($graph === null) {
            return null;
        }

        foreach ($keys as $key) {
            $subkeys = explode('.', $key);

            foreach ($graph->getNodes() as $node) {
                $value = self::getValue($node, ...$subkeys);

                if ($value !== null && $value !== '' && $value !== false && $value !== []) {
                    return $value;
                }
            }
        }

        return null;
    }

    /**
     * @return array<string, mixed>
     */
    public function getAll(): array
    {
        if ($this->allData === []) {
            $this->fetchData();
        }

        return $this->allData;
    }

    private function getGraph(?string $name = null): ?GraphInterface
    {
        if (!isset($this->document)) {
            try {
                $encoded = json_encode($this->all());
                if ($encoded === false) {
                    $encoded = '{}';
                }
                $this->document = LdDocument::load($encoded);
            } catch (Throwable $throwable) {
                $this->document = LdDocument::load('{}');
                return null;
            }
        }

        return $this->document->getGraph($name);
    }

    /**
     * @return array<string, mixed>
     */
    protected function fetchData(): array
    {
        $this->allData = [];

        $document = $this->extractor->getDocument();
        $nodes = $document->select('.//script', ['type' => 'application/ld+json'])->strAll();

        if ($nodes === []) {
            return [];
        }

        try {
            /** @var array<string, mixed> $data */
            $data = [];
            $request_uri = (string)$this->extractor->getUri();
            foreach ($nodes as $node) {
                $ldjson = json_decode($node, true);
                if (is_array($ldjson) && $ldjson !== []) {

                    // some pages with multiple ld+json blocks will put
                    // each block into an array (Flickr does this). Most
                    // appear to put an object in each ld+json block. To
                    // prevent them from stepping on one another, the ones
                    // that are not arrays will be put into an array.
                    if (!array_is_list($ldjson)) {
                        $ldjson = [$ldjson];
                    }

                    foreach ($ldjson as $ldNode) {
                        if (!is_array($ldNode)) {
                            continue;
                        }
                        if ($data === []) {
                            /** @var array<string, mixed> $data */
                            $data = $ldNode;
                        } elseif (isset($ldNode['mainEntityOfPage'])) {
                            $url = '';
                            if (is_string($ldNode['mainEntityOfPage'])) {
                                $url = $ldNode['mainEntityOfPage'];
                            } elseif (is_array($ldNode['mainEntityOfPage']) && isset($ldNode['mainEntityOfPage']['@id']) && is_string($ldNode['mainEntityOfPage']['@id'])) {
                                $url = $ldNode['mainEntityOfPage']['@id'];
                            }
                            if ($url !== '' && $url === $request_uri) {
                                /** @var array<string, mixed> $data */
                                $data = $ldNode;
                            }
                        }
                    }

                    /** @var array<string, mixed> $mergedData */
                    $mergedData = array_merge($this->allData, $ldjson);
                    $this->allData = $mergedData;
                }
            }

            return $data;
        } catch (Exception $exception) {
            return [];
        }
    }

    /**
     * @return mixed
     */
    private static function getValue(Node $node, string ...$keys)
    {
        foreach ($keys as $key) {
            if (is_array($node)) {
                $node = array_shift($node);
            }
            if (!$node instanceof Node) {
                return null;
            }

            $node = $node->getProperty("http://schema.org/{$key}");

            if ($node === null) {
                return null;
            }
        }

        return self::detectValue($node);
    }

    /**
     * @param mixed $value
     * @return mixed
     */
    private static function detectValue($value)
    {
        if (is_array($value)) {
            return array_map(
                fn ($val) => self::detectValue($val),
                array_values($value)
            );
        }

        if (is_scalar($value)) {
            return $value;
        }

        if ($value instanceof Node) {
            return $value->getId();
        }

        if (is_object($value) && method_exists($value, 'getValue')) {
            return $value->getValue();
        }

        return null;
    }
}
