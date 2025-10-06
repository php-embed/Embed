<?php
declare(strict_types = 1);

namespace Embed;

use Psr\Http\Message\UriInterface;

function clean(string $value, bool $allowHTML = false): ?string
{
    $value = trim($value);

    if (!$allowHTML) {
        $value = html_entity_decode($value, ENT_QUOTES | ENT_SUBSTITUTE | ENT_HTML401);
        $value = strip_tags($value);
    }

    $replaced = preg_replace('/\s+/u', ' ', $value);
    $value = trim($replaced !== null ? $replaced : $value);
    return $value === '' ? null : $value;
}

/**
 * @param array<string, mixed> $attributes
 */
function html(string $tagName, array $attributes, ?string $content = null): string
{
    $html = "<{$tagName}";

    foreach ($attributes as $name => $value) {
        if ($value === null) {
            continue;
        } elseif ($value === true) {
            $html .= " $name";
        } elseif ($value !== false) {
            if (is_string($value)) {
                $stringValue = $value;
            } elseif (is_scalar($value)) {
                $stringValue = (string) $value;
            } elseif (is_object($value) && method_exists($value, '__toString')) {
                $stringValue = (string) $value;
            } else {
                $stringValue = '';
            }
            $html .= ' '.$name.'="'.htmlspecialchars($stringValue).'"';
        }
    }

    if ($tagName === 'img') {
        return "$html />";
    }

    return "{$html}>{$content}</{$tagName}>";
}

/**
 * Resolve a uri within this document
 * (useful to get absolute uris from relative)
 */
function resolveUri(UriInterface $base, UriInterface $uri): UriInterface
{
    $uri = $uri->withPath(resolvePath($base->getPath(), $uri->getPath()));

    if ($uri->getHost() === '') {
        $uri = $uri->withHost($base->getHost());
    }

    if ($uri->getScheme() === '') {
        $uri = $uri->withScheme($base->getScheme());
    }

    return $uri
        ->withPath(cleanPath($uri->getPath()))
        ->withFragment('');
}

function isHttp(string $uri): bool
{
    $result = preg_match('/^(\w+):/', $uri, $matches);
    if ($result !== false && $result > 0) {
        return in_array(strtolower($matches[1]), ['http', 'https'], true);
    }

    return true;
}

function resolvePath(string $base, string $path): string
{
    if ($path === '') {
        return '';
    }

    if ($path[0] === '/') {
        return $path;
    }

    if (substr($base, -1) !== '/') {
        $position = strrpos($base, '/');
        $base = $position !== false ? substr($base, 0, $position) : '';
    }

    $path = "{$base}/{$path}";

    $parts = array_filter(explode('/', $path), static function (string $value): bool {
        return strlen($value) > 0;
    });
    $absolutes = [];

    foreach ($parts as $part) {
        if ('.' === $part) {
            continue;
        }

        if ('..' === $part) {
            array_pop($absolutes);
            continue;
        }

        $absolutes[] = $part;
    }

    return implode('/', $absolutes);
}

function cleanPath(?string $path): string
{
    if ($path === null || $path === '') {
        return '/';
    }

    $cleanedPath = preg_replace('|[/]{2,}|', '/', $path);
    if ($cleanedPath === null) {
        return '/';
    }
    $path = $cleanedPath;

    if (strpos($path, ';jsessionid=') !== false) {
        $cleanedPath = preg_replace('/^(.*)(;jsessionid=.*)$/i', '$1', $path);
        if ($cleanedPath !== null) {
            $path = $cleanedPath;
        }
    }

    return $path;
}

function matchPath(string $pattern, string $subject): bool
{
    $pattern = str_replace('\\*', '.*', preg_quote($pattern, '|'));

    return (bool) preg_match("|^{$pattern}$|i", $subject);
}

function getDirectory(string $path, int $position): ?string
{
    $dirs = explode('/', $path);
    return $dirs[$position + 1] ?? null;
}

/**
 * Determine whether at least one of the supplied variables is empty.
 *
 * @param mixed ...$values The values to check.
 *
 * @return boolean
 */
function isEmpty(...$values): bool
{
    $skipValues = array(
        'undefined',
    );

    foreach ($values as $value) {
        if ($value === null || $value === '' || $value === [] || $value === false || $value === 0 || $value === 0.0 || $value === '0' || in_array($value, $skipValues, true)) {
            return true;
        }
    }

    return false;
}

if (!function_exists("array_is_list")) {
    /**
     * Polyfil for https://www.php.net/manual/en/function.array-is-list.php
     * which is only available in PHP 8.1+
     *
     * @param      array<mixed, mixed>  $array  The array
     *
     * @return     bool
     */
    function array_is_list(array $array): bool
    {
        $i = -1;
        foreach ($array as $k => $v) {
            ++$i;
            if ($k !== $i) {
                return false;
            }
        }
        return true;
    }
}
