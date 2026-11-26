<?php
declare(strict_types = 1);

namespace Embed\JsonLd;

/**
 * Compact JSON-LD expander used by LinkedData.
 *
 * It only implements what Embed needs: @context processing (including the
 * bundled schema.org vocabulary), term expansion to http://schema.org/*,
 * @graph flattening, and walking nested nodes. Remote contexts other than
 * schema.org are refused by DocumentLoader.
 */
final class Expander
{
    /** @var array<string, true> */
    private const KEYWORDS = [
        '@base' => true,
        '@container' => true,
        '@context' => true,
        '@direction' => true,
        '@graph' => true,
        '@id' => true,
        '@import' => true,
        '@included' => true,
        '@index' => true,
        '@json' => true,
        '@language' => true,
        '@list' => true,
        '@nest' => true,
        '@none' => true,
        '@prefix' => true,
        '@propagate' => true,
        '@protected' => true,
        '@reverse' => true,
        '@set' => true,
        '@type' => true,
        '@value' => true,
        '@version' => true,
        '@vocab' => true,
    ];

    /**
     * Expand compact JSON-LD into a flat list of nodes.
     *
     * @param array<mixed> $data
     *
     * @return array<int, array<string, mixed>>
     */
    public static function expand(array $data): array
    {
        $expanded = self::expandElement($data, self::emptyContext());

        return self::collectNodes($expanded);
    }

    /**
     * @return array{vocab:?string, language:?string, terms:array<string, array{id:string, type:?string}>}
     */
    private static function emptyContext(): array
    {
        return [
            'vocab' => null,
            'language' => null,
            'terms' => [],
        ];
    }

    /**
     * @param mixed $element
     * @param array{vocab:?string, language:?string, terms:array<string, array{id:string, type:?string}>} $ctx
     *
     * @return mixed
     */
    private static function expandElement($element, array $ctx)
    {
        if ($element === null) {
            return null;
        }

        if (is_string($element) || is_int($element) || is_float($element) || is_bool($element)) {
            return self::valueObject($element, $ctx);
        }

        if (!is_array($element)) {
            throw new JsonLdException('Invalid JSON-LD value.');
        }

        if (self::isList($element)) {
            $out = [];
            foreach ($element as $item) {
                $expanded = self::expandElement($item, $ctx);
                if ($expanded === null) {
                    continue;
                }
                $out[] = $expanded;
            }

            return $out;
        }

        if (array_key_exists('@context', $element)) {
            $ctx = self::processContext($element['@context'], $ctx);
        }

        if (array_key_exists('@value', $element)) {
            return self::expandValueObject(self::stringKeyed($element), $ctx);
        }

        if (array_key_exists('@list', $element)) {
            return self::expandElement($element['@list'], $ctx);
        }

        if (array_key_exists('@set', $element)) {
            return self::expandElement($element['@set'], $ctx);
        }

        $node = [];

        if (isset($element['@id']) && is_string($element['@id']) && $element['@id'] !== '') {
            $node['@id'] = self::expandIri($element['@id'], $ctx, false);
        }

        if (isset($element['@type'])) {
            $types = is_array($element['@type']) ? $element['@type'] : [$element['@type']];
            $expandedTypes = [];
            foreach ($types as $type) {
                if (is_string($type) && $type !== '') {
                    $expandedTypes[] = self::expandIri($type, $ctx, true);
                }
            }
            if ($expandedTypes !== []) {
                $node['@type'] = $expandedTypes;
            }
        }

        if (isset($element['@graph']) && is_array($element['@graph'])) {
            $graph = self::expandElement($element['@graph'], $ctx);
            $node['@graph'] = self::asList($graph);
        }

        foreach ($element as $key => $value) {
            if (!is_string($key) || $key === '' || isset(self::KEYWORDS[$key])) {
                continue;
            }

            $property = self::expandIri($key, $ctx, true);
            if ($property === '' || isset(self::KEYWORDS[$property])) {
                continue;
            }

            $termType = $ctx['terms'][$key]['type'] ?? null;
            $expandedValues = self::expandPropertyValue($value, $ctx, $termType);
            if ($expandedValues === []) {
                continue;
            }

            $node[$property] = $expandedValues;
        }

        return $node;
    }

    /**
     * @param mixed $value
     * @param array{vocab:?string, language:?string, terms:array<string, array{id:string, type:?string}>} $ctx
     *
     * @return array<int, mixed>
     */
    private static function expandPropertyValue($value, array $ctx, ?string $termType): array
    {
        if ($value === null) {
            return [];
        }

        $items = (is_array($value) && self::isList($value)) ? $value : [$value];
        $out = [];

        foreach ($items as $item) {
            if ($termType === '@id' && is_string($item) && $item !== '') {
                $out[] = ['@id' => self::expandIri($item, $ctx, false)];
                continue;
            }

            $expanded = self::expandElement($item, $ctx);
            if ($expanded === null) {
                continue;
            }

            if (is_array($expanded) && self::isList($expanded)) {
                foreach ($expanded as $entry) {
                    $out[] = $entry;
                }
                continue;
            }

            $out[] = $expanded;
        }

        return $out;
    }

    /**
     * @param array<string, mixed> $element
     * @param array{vocab:?string, language:?string, terms:array<string, array{id:string, type:?string}>} $ctx
     *
     * @return array<string, mixed>
     */
    private static function expandValueObject(array $element, array $ctx): array
    {
        $value = ['@value' => $element['@value']];
        if (isset($element['@language']) && is_string($element['@language'])) {
            $value['@language'] = $element['@language'];
        } elseif (is_string($element['@value']) && is_string($ctx['language']) && $ctx['language'] !== '') {
            $value['@language'] = $ctx['language'];
        }
        if (isset($element['@type']) && is_string($element['@type'])) {
            $value['@type'] = self::expandIri($element['@type'], $ctx, true);
        }

        return $value;
    }

    /**
     * @param mixed $value
     * @param array{vocab:?string, language:?string, terms:array<string, array{id:string, type:?string}>} $ctx
     *
     * @return array<string, mixed>
     */
    private static function valueObject($value, array $ctx): array
    {
        $result = ['@value' => $value];
        if (is_string($value) && is_string($ctx['language']) && $ctx['language'] !== '') {
            $result['@language'] = $ctx['language'];
        }

        return $result;
    }

    /**
     * @param mixed $context
     * @param array{vocab:?string, language:?string, terms:array<string, array{id:string, type:?string}>} $ctx
     *
     * @return array{vocab:?string, language:?string, terms:array<string, array{id:string, type:?string}>}
     */
    private static function processContext($context, array $ctx): array
    {
        if ($context === null) {
            return self::emptyContext();
        }

        if (is_string($context)) {
            $document = (new DocumentLoader())->loadDocument($context)->document;
            $decoded = self::toArray($document);
            if (is_array($decoded) && array_key_exists('@context', $decoded)) {
                return self::processContext($decoded['@context'], $ctx);
            }

            return self::processContext($decoded, $ctx);
        }

        if (!is_array($context)) {
            throw new JsonLdException('Invalid JSON-LD context.');
        }

        if (self::isList($context)) {
            foreach ($context as $item) {
                $ctx = self::processContext($item, $ctx);
            }

            return $ctx;
        }

        if (array_key_exists('@vocab', $context)) {
            $vocab = $context['@vocab'];
            if ($vocab === null || $vocab === '') {
                $ctx['vocab'] = null;
            } elseif (is_string($vocab)) {
                $ctx['vocab'] = $vocab;
            } else {
                throw new JsonLdException('Invalid JSON-LD @vocab.');
            }
        }

        if (array_key_exists('@language', $context)) {
            $language = $context['@language'];
            $ctx['language'] = is_string($language) ? $language : null;
        }

        foreach ($context as $term => $definition) {
            if (!is_string($term) || $term === '' || isset(self::KEYWORDS[$term])) {
                continue;
            }

            if (is_string($definition) && $definition !== '') {
                $ctx['terms'][$term] = [
                    'id' => self::expandIri($definition, $ctx, true),
                    'type' => null,
                ];
                continue;
            }

            if (!is_array($definition)) {
                continue;
            }

            $id = $definition['@id'] ?? $term;
            if (!is_string($id) || $id === '') {
                continue;
            }

            $type = $definition['@type'] ?? null;
            $ctx['terms'][$term] = [
                'id' => self::expandIri($id, $ctx, true),
                'type' => is_string($type) ? $type : null,
            ];
        }

        return $ctx;
    }

    /**
     * @param array{vocab:?string, language:?string, terms:array<string, array{id:string, type:?string}>} $ctx
     */
    private static function expandIri(string $value, array $ctx, bool $relativeToVocab): string
    {
        if ($value === '' || isset(self::KEYWORDS[$value])) {
            return $value;
        }

        if (isset($ctx['terms'][$value])) {
            return $ctx['terms'][$value]['id'];
        }

        if (preg_match('#^[a-zA-Z][a-zA-Z0-9+.-]*://#', $value) === 1) {
            return $value;
        }

        $colon = strpos($value, ':');
        if ($colon !== false) {
            $prefix = substr($value, 0, $colon);
            $suffix = substr($value, $colon + 1);
            if (isset($ctx['terms'][$prefix]) && strpos($suffix, '//') !== 0) {
                return $ctx['terms'][$prefix]['id'].$suffix;
            }
            if (preg_match('/^[a-zA-Z][a-zA-Z0-9+.-]*$/', $prefix) === 1) {
                return $value;
            }
        }

        if ($relativeToVocab && is_string($ctx['vocab']) && $ctx['vocab'] !== '') {
            return $ctx['vocab'].$value;
        }

        return $value;
    }

    /**
     * @param mixed $expanded
     *
     * @return array<int, array<string, mixed>>
     */
    private static function collectNodes($expanded): array
    {
        $nodes = [];
        self::walkNodes($expanded, $nodes);

        return $nodes;
    }

    /**
     * @param mixed $expanded
     * @param array<int, array<string, mixed>> $nodes
     */
    private static function walkNodes($expanded, array &$nodes): void
    {
        if (!is_array($expanded)) {
            return;
        }

        if (self::isList($expanded)) {
            foreach ($expanded as $item) {
                self::walkNodes($item, $nodes);
            }

            return;
        }

        if (array_key_exists('@value', $expanded)) {
            return;
        }

        if (isset($expanded['@graph']) && is_array($expanded['@graph'])) {
            $hasOtherProperties = false;
            foreach ($expanded as $key => $_) {
                if ($key !== '@graph' && $key !== '@id' && $key !== '@type') {
                    $hasOtherProperties = true;
                    break;
                }
            }
            if ($hasOtherProperties) {
                $nodes[] = self::stringKeyed($expanded);
            }
            self::walkNodes($expanded['@graph'], $nodes);

            return;
        }

        $nodes[] = self::stringKeyed($expanded);

        foreach ($expanded as $key => $value) {
            if ($key === '@id' || $key === '@type') {
                continue;
            }
            self::walkNodes($value, $nodes);
        }
    }

    /**
     * JSON lists are 0-indexed arrays. LinkedData lives in namespace Embed,
     * so it can see the array_is_list polyfill in functions.php; this class
     * cannot, and PHP 7.4 / 8.0 have no global array_is_list.
     *
     * @param array<mixed> $value
     */
    private static function isList(array $value): bool
    {
        $i = 0;
        foreach ($value as $key => $_) {
            if ($key !== $i) {
                return false;
            }
            ++$i;
        }

        return true;
    }

    /**
     * @param array<mixed> $value
     *
     * @return array<string, mixed>
     */
    private static function stringKeyed(array $value): array
    {
        $result = [];
        foreach ($value as $key => $item) {
            if (is_string($key)) {
                $result[$key] = $item;
            }
        }

        return $result;
    }

    /**
     * @param mixed $value
     *
     * @return array<int, mixed>
     */
    private static function asList($value): array
    {
        if ($value === null) {
            return [];
        }
        if (is_array($value) && self::isList($value)) {
            return array_values($value);
        }

        return [$value];
    }

    /**
     * @param mixed $value
     *
     * @return mixed
     */
    private static function toArray($value)
    {
        if (is_object($value)) {
            $value = get_object_vars($value);
        }
        if (!is_array($value)) {
            return $value;
        }

        $result = [];
        foreach ($value as $key => $item) {
            $result[$key] = self::toArray($item);
        }

        return $result;
    }
}
