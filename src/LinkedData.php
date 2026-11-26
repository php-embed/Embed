<?php
declare(strict_types = 1);

namespace Embed;

use Embed\JsonLd\Expander;
use Exception;
use Throwable;

class LinkedData
{
    use ApiTrait;

    /** @var array<int, array<string, mixed>>|null */
    private ?array $graph = null;

    private bool $graphLoaded = false;

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

            foreach ($graph as $node) {
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

    /**
     * @return array<int, array<string, mixed>>|null
     */
    private function getGraph(): ?array
    {
        if ($this->graphLoaded) {
            return $this->graph;
        }

        $this->graphLoaded = true;

        try {
            $this->graph = Expander::expand($this->all());
        } catch (Throwable $throwable) {
            $this->graph = null;
        }

        return $this->graph;
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
     * @param mixed $node
     * @return mixed
     */
    private static function getValue($node, string ...$keys)
    {
        foreach ($keys as $key) {
            if (is_array($node) && array_is_list($node)) {
                $node = array_shift($node);
            }
            if (!is_array($node) || array_key_exists('@value', $node)) {
                return null;
            }

            $property = 'http://schema.org/'.$key;
            if (!array_key_exists($property, $node)) {
                return null;
            }

            $node = $node[$property];
            if (is_array($node) && array_is_list($node) && count($node) === 1) {
                $node = $node[0];
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
        if (is_array($value) && array_is_list($value)) {
            return array_map(
                static fn ($val) => self::detectValue($val),
                array_values($value)
            );
        }

        if (is_scalar($value)) {
            return $value;
        }

        if (!is_array($value)) {
            return null;
        }

        if (array_key_exists('@value', $value)) {
            $inner = $value['@value'];
            return is_scalar($inner) ? $inner : null;
        }

        if (isset($value['@id']) && is_string($value['@id'])) {
            return $value['@id'];
        }

        return null;
    }
}
