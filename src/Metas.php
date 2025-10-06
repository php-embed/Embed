<?php
declare(strict_types = 1);

namespace Embed;

class Metas
{
    use ApiTrait;

    /**
     * @return array<string, mixed>
     */
    protected function fetchData(): array
    {
        $data = [];
        $document = $this->extractor->getDocument();

        foreach ($document->select('.//meta')->nodes() as $node) {
            if (!($node instanceof \DOMElement)) {
                continue;
            }
            $type = $node->getAttribute('name');
            if ($type === '') {
                $type = $node->getAttribute('property');
            }
            if ($type === '') {
                $type = $node->getAttribute('itemprop');
            }
            $value = $node->getAttribute('content');

            if ($value !== '' && $type !== '') {
                $type = strtolower($type);
                $data[$type] ??= [];
                $data[$type][] = $value;
            }
        }

        return $data;
    }

    /**
     * @return mixed
     */
    public function get(string ...$keys)
    {
        $data = $this->all();

        foreach ($keys as $key) {
            $values = $data[$key] ?? null;

            if ($values !== null && $values !== '' && $values !== []) {
                return $values;
            }
        }

        return null;
    }
}
