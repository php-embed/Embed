<?php
declare(strict_types = 1);

namespace Embed\Http;

use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Message\UriFactoryInterface;
use RuntimeException;

abstract class FactoryDiscovery
{
    private const REQUEST = [
        'Laminas\Diactoros\RequestFactory',
        'GuzzleHttp\Psr7\HttpFactory',
        'Slim\Psr7\Factory\RequestFactory',
        'Nyholm\Psr7\Factory\Psr17Factory',
        'Sunrise\Http\Message\RequestFactory',
    ];

    private const RESPONSE = [
        'Laminas\Diactoros\ResponseFactory',
        'GuzzleHttp\Psr7\HttpFactory',
        'Slim\Psr7\Factory\ResponseFactory',
        'Nyholm\Psr7\Factory\Psr17Factory',
        'Sunrise\Http\Message\ResponseFactory',
    ];

    private const URI = [
        'Laminas\Diactoros\UriFactory',
        'GuzzleHttp\Psr7\HttpFactory',
        'Slim\Psr7\Factory\UriFactory',
        'Nyholm\Psr7\Factory\Psr17Factory',
        'Sunrise\Http\Message\UriFactory',
    ];

    private const STREAM = [
        'Laminas\Diactoros\StreamFactory',
        'GuzzleHttp\Psr7\HttpFactory',
        'Slim\Psr7\Factory\StreamFactory',
        'Nyholm\Psr7\Factory\Psr17Factory',
        'Sunrise\Http\Message\StreamFactory',
    ];

    public static function getRequestFactory(): RequestFactoryInterface
    {
        $class = self::searchClass(self::REQUEST);
        if ($class !== null) {
            /** @var RequestFactoryInterface */
            return new $class();
        }

        throw new RuntimeException('No RequestFactoryInterface detected');
    }

    public static function getResponseFactory(): ResponseFactoryInterface
    {
        $class = self::searchClass(self::RESPONSE);
        if ($class !== null) {
            /** @var ResponseFactoryInterface */
            return new $class();
        }

        throw new RuntimeException('No ResponseFactoryInterface detected');
    }

    public static function getUriFactory(): UriFactoryInterface
    {
        $class = self::searchClass(self::URI);
        if ($class !== null) {
            /** @var UriFactoryInterface */
            return new $class();
        }

        throw new RuntimeException('No UriFactoryInterface detected');
    }

    public static function getStreamFactory(): StreamFactoryInterface
    {
        $class = self::searchClass(self::STREAM);
        if ($class !== null) {
            /** @var StreamFactoryInterface */
            return new $class();
        }

        throw new RuntimeException('No StreamFactoryInterface detected');
    }

    /**
     * @param string[] $classes
     */
    private static function searchClass(array $classes): ?string
    {
        foreach ($classes as $class) {
            if (class_exists($class)) {
                return $class;
            }
        }

        return null;
    }
}
