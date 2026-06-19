# Embed


[![Latest Version on Packagist][ico-version]][link-packagist]
[![Total Downloads][ico-downloads]][link-packagist]
[![Monthly Downloads][ico-m-downloads]][link-packagist]
[![Software License][ico-license]](LICENSE)

PHP library to get information from any web page (using oembed, opengraph, twitter-cards, scrapping the html, etc). It's compatible with any web service (youtube, vimeo, flickr, instagram, etc) and has adapters to some sites like (archive.org, github, facebook, etc).

Requirements:

* PHP 7.4+
* Curl library installed
* PSR-17 implementation. By default these libraries are detected automatically:
  * [laminas/laminas-diactoros](https://github.com/laminas/laminas-diactoros)
  * [guzzle/psr7](https://github.com/guzzle/psr7)
  * [nyholm/psr7](https://github.com/Nyholm/psr7)
  * [sunrise/http-message](https://github.com/sunrise-php/http-message)

> If you need PHP 5.5-7.3 support, [use the 3.x version](https://github.com/oscarotero/Embed/tree/v3.x)

## Online demo

Run `php -S localhost:8888 demo/index.php`

## Video Tutorial
 [<img src="https://img.youtube.com/vi/4YCLRpKY1cs/0.jpg" width="250">](https://youtu.be/4YCLRpKY1cs)
 

## Installation

This package is installable and autoloadable via Composer as [embed/embed](https://packagist.org/packages/embed/embed).

```
$ composer require embed/embed
```

## Usage

```php
use Embed\Embed;

$embed = new Embed();

//Load any url:
$info = $embed->get('https://www.youtube.com/watch?v=PP1xn5wHtxE');

//Get content info

$info->title; //The page title
$info->description; //The page description
$info->url; //The canonical url
$info->keywords; //The page keywords

$info->image; //The thumbnail or main image

$info->code->html; //The code to embed the image, video, etc
$info->code->width; //The exact width of the embed code (if exists)
$info->code->height; //The exact height of the embed code (if exists)
$info->code->ratio; //The percentage of height / width to emulate the aspect ratio using paddings.

$info->authorName; //The resource author
$info->authorUrl; //The author url

$info->cms; //The cms used
$info->language; //The language of the page
$info->languages; //The alternative languages

$info->providerName; //The provider name of the page (Youtube, Twitter, Instagram, etc)
$info->providerUrl; //The provider url
$info->icon; //The big icon of the site
$info->favicon; //The favicon of the site (an .ico file or a png with up to 32x32px)

$info->publishedTime; //The published time of the resource
$info->license; //The license url of the resource
$info->feeds; //The RSS/Atom feeds
```

## Parallel multiple requests

```php
use Embed\Embed;

$embed = new Embed();

//Load multiple urls asynchronously:
$infos = $embed->getMulti(
    'https://www.youtube.com/watch?v=PP1xn5wHtxE',
    'https://twitter.com/carlosmeixidefl/status/1230894146220625933',
    'https://en.wikipedia.org/wiki/Tordoia',
);

foreach ($infos as $info) {
    echo $info->title;
}
```

## Document

The document is the object that store the html code of the page. You can use it to extract extra info from the html code:

```php
//Get the document object
$document = $info->getDocument();

$document->link('image_src'); //Returns the href of a <link>
$document->getDocument(); //Returns the DOMDocument instance
$html = (string) $document; //Returns the html code

$document->select('.//h1'); //Search
```

You can perform xpath queries in order to select specific elements. A search always return an instance of a `Embed\QueryResult`:

```php
//Search the A elements
$result = $document->select('.//a');

//Filter the results
$result->filter(fn ($node) => $node->getAttribute('href'));

$id = $result->str('id'); //Return the id of the first result as string
$text = $result->str(); //Return the content of the first result

$ids = $result->strAll('id'); //Return an array with the ids of all results as string
$texts = $result->strAll(); //Return an array with the content of all results as string

$tabindex = $result->int('tabindex'); //Return the tabindex attribute of the first result as integer
$number = $result->int(); //Return the content of the first result as integer

$href = $result->url('href'); //Return the href attribute of the first result as url (converts relative urls to absolutes)
$url = $result->url(); //Return the content of the first result as url

$node = $result->node(); //Return the first node found (DOMElement)
$nodes = $result->nodes(); //Return all nodes found
```

## Metas

For convenience, the object `Metas` stores the value of all `<meta>` elements located in the html, so you can get the values easier. The key of every meta is get from the `name`, `property` or `itemprop` attributes and the value is get from `content`.

```php
//Get the Metas object
$metas = $info->getMetas();

$metas->all(); //Return all values
$metas->get('og:title'); //Return a key value
$metas->str('og:title'); //Return the value as string (remove html tags)
$metas->html('og:description'); //Return the value as html
$metas->int('og:video:width'); //Return the value as integer
$metas->url('og:url'); //Return the value as full url (converts relative urls to absolutes)
```

## OEmbed

In addition to the html and metas, this library uses [oEmbed](https://oembed.com/) endpoints to get additional data. You can get this data as following:

```php
//Get the oEmbed object
$oembed = $info->getOEmbed();

$oembed->all(); //Return all raw data
$oembed->get('title'); //Return a key value
$oembed->str('title'); //Return the value as string (remove html tags)
$oembed->html('html'); //Return the value as html
$oembed->int('width'); //Return the value as integer
$oembed->url('url'); //Return the value as full url (converts relative urls to absolutes)
```

Additional oEmbed parameters (like instagrams `hidecaption`) can also be provided:
```php
$embed = new Embed();

$result = $embed->get('https://www.instagram.com/p/B_C0wheCa4V/');
$result->setSettings([
    'oembed:query_parameters' => ['hidecaption' => true]
]);
$oembed = $info->getOEmbed();
```

## LinkedData

Another API available by default, used to extract info using the [JsonLD](https://www.w3.org/TR/json-ld/) schema.

```php
//Get the linkedData object
$ld = $info->getLinkedData();

$ld->all(); //Return all data
$ld->get('name'); //Return a key value
$ld->str('name'); //Return the value as string (remove html tags)
$ld->html('description'); //Return the value as html
$ld->int('width'); //Return the value as integer
$ld->url('url'); //Return the value as full url (converts relative urls to absolutes)
```

## Other APIs

Some sites like Wikipedia or Archive.org provide a custom API that is used to fetch more reliable data. You can get the API object with the method `getApi()` but note that not all results have this method. The Api object has the same methods than oEmbed:

```php
//Get the API object
$api = $info->getApi();

$api->all(); //Return all raw data
$api->get('title'); //Return a key value
$api->str('title'); //Return the value as string (remove html tags)
$api->html('html'); //Return the value as html
$api->int('width'); //Return the value as integer
$api->url('url'); //Return the value as full url (converts relative urls to absolutes)
```

## Extending Embed

Depending of your needs, you may want to extend this library with extra features or change the way it makes some operations.

### PSR

Embed use some PSR standards to be the most interoperable possible:

- [PSR-7](https://www.php-fig.org/psr/psr-7/) Standard interfaces to represent http requests, responses and uris
- [PSR-17](https://www.php-fig.org/psr/psr-17/) Standard factories to create PSR-7 objects
- [PSR-18](https://www.php-fig.org/psr/psr-18/) Standard interface to send a http request and return a response

Embed comes with a CURL client compatible with PSR-18 but you need to install a PSR-7 / PSR-17 library. [Here you can see a list of popular libraries](https://github.com/middlewares/awesome-psr15-middlewares#psr-7-implementations) and the library can detect automatically 'laminas\diactoros', 'guzzleHttp\psr7', 'slim\psr7', 'nyholm\psr7' and 'sunrise\http' (in this order). If you want to use a different PSR implementation, you can do it in this way:

```php
use Embed\Embed;
use Embed\Http\Crawler;

$client = new CustomHttpClient();
$requestFactory = new CustomRequestFactory();
$uriFactory = new CustomUriFactory();

//The Crawler is responsible for perform http queries
$crawler = new Crawler($client, $requestFactory, $uriFactory);

//Create an embed instance passing the Crawler
$embed = new Embed($crawler);
```

### Adapters

There are some sites with special needs: because they provide public APIs that allows to extract more info (like Wikipedia or Archive.org) or because we need to change how to extract the data in this particular site. For all that cases we have the adapters, that are classes extending the default classes to provide extra functionality.

Before creating an adapter, you need to understand how Embed work: when you execute this code, you get a `Extractor` class

```php
//Get the Extractor with all info
$info = $embed->get($url);

//The extractor have document and oembed:
$document = $info->getDocument();
$oembed = $info->getOEmbed();
```

The `Extractor` class has many `Detectors`. Each detector is responsible to detect a specific piece of info. For example, there's a detector for the title, other for description, image, code, etc.

So, an adapter is basically an extractor created specifically for a site. It can contains also custom detectors or apis. If you see the `src/Adapters` folder you can see all adapters.

If you create an adapter, you need also register to Embed, so it knows in which website needs to use. To do that, there's the `ExtractorFactory` object, that is responsible for instantiate the right extractor for each site.

```php
use Embed\Embed;

$embed = new Embed();

$factory = $embed->getExtractorFactory();

//Use this MySite adapter for mysite.com
$factory->addAdapter('mysite.com', MySite::class);

//Remove the adapter for pinterest.com, so it will use the default extractor
$factory->removeAdapter('pinterest.com');

//Change the default extractor
$factory->setDefault(CustomExtractor::class);
```

### Detectors

Embed comes with several predefined detectors, but you may want to change or add more. Just create a class extending `Embed\Detectors\Detector` class and register it in the extractor factory. For example:

```php
use Embed\Embed;
use Embed\Detectors\Detector;

class Robots extends Detector
{
    public function detect(): ?string
    {
        $response = $this->extractor->getResponse();
        $metas = $this->extractor->getMetas();

        return $response->getHeaderLine('x-robots-tag'),
            ?: $metas->str('robots');
    }
}

//Register the detector
$embed = new Embed();
$embed->getExtractorFactory()->addDetector('robots', Robots::class);

//Use it
$info = $embed->get('http://example.com');
$robots = $info->robots;
```

### Settings

If you need to pass settings to the CurlClient to perform http queries:

```php
use Embed\Embed;
use Embed\Http\Crawler;
use Embed\Http\CurlClient;

$client = new CurlClient();
$client->setSettings([
    'cookies_path' => $cookies_path,
    'ignored_errors' => [18],
    'max_redirs' => 3,               // see CURLOPT_MAXREDIRS
    'connect_timeout' => 2,          // see CURLOPT_CONNECTTIMEOUT
    'timeout' => 2,                  // see CURLOPT_TIMEOUT
    'ssl_verify_host' => 2,          // see CURLOPT_SSL_VERIFYHOST
    'ssl_verify_peer' => 1,          // see CURLOPT_SSL_VERIFYPEER
    'follow_location' => true,       // see CURLOPT_FOLLOWLOCATION
    'user_agent' => 'Mozilla',       // see CURLOPT_USERAGENT
]);

$embed = new Embed(new Crawler($client));
```

If you need to pass settings to your detectors, you can add settings to the `ExtractorFactory`:

```php
use Embed\Embed;

$embed = new Embed();
$embed->setSettings([
    'oembed:query_parameters' => [],  //Extra parameters send to oembed
    'twitch:parent' => 'example.com', //Required to embed twitch videos as iframe
    'facebook:token' => '1234|5678',  //Required to embed content from Facebook
    'instagram:token' => '1234|5678', //Required to embed content from Instagram
    'twitter:token' => 'asdf',        //Improve the data from twitter
]);
$info = $embed->get($url);
```

Note: The built-in detectors does not require settings. This feature is only for convenience if you create a specific detector that requires settings.

---

## Testing

### Running tests

```bash
composer test
# or
./vendor/bin/phpunit
```

### Snapshot modes

The test suite uses cached HTTP responses and fixtures to avoid network requests during testing. You can control this behavior using environment variables:

| Environment Variable | Description |
|---------------------|-------------|
| `UPDATE_EMBED_SNAPSHOTS=1` | Fetch from network and update both cache and fixtures |
| `EMBED_STRICT_CACHE=1` | Fail if cache or fixture doesn't exist (useful for CI) |

By default (no environment variables set), tests read from cache and generate missing files automatically.

**Note:** If both `UPDATE_EMBED_SNAPSHOTS` and `EMBED_STRICT_CACHE` are set, `UPDATE_EMBED_SNAPSHOTS` takes precedence.

### Cache structure

The test framework uses two types of cached data:

- **Response cache** (`tests/cache/`): Cached HTTP responses from external sites
- **Fixtures** (`tests/fixtures/`): Expected test results (metadata extracted from cached responses)

### How to update cache

#### When a website changes its HTML structure

If a website updates its HTML and you need to update the cached response and fixture:

```bash
# Update cache and fixture for a specific test
UPDATE_EMBED_SNAPSHOTS=1 ./vendor/bin/phpunit --filter testYoutube
```

#### When adding a new test

After adding a new URL to test:

```bash
# This will fetch the response and create both cache and fixture
UPDATE_EMBED_SNAPSHOTS=1 ./vendor/bin/phpunit --filter testNewSite
```

#### Update all caches at once

To refresh all cached responses and fixtures from the network:

```bash
UPDATE_EMBED_SNAPSHOTS=1 ./vendor/bin/phpunit
```

#### For CI environments

Ensure all tests run strictly from cache (fail if any cache is missing):

```bash
EMBED_STRICT_CACHE=1 ./vendor/bin/phpunit
```

---

[ico-version]: https://poser.pugx.org/embed/embed/v/stable
[ico-license]: https://poser.pugx.org/embed/embed/license
[ico-downloads]: https://poser.pugx.org/embed/embed/downloads
[ico-m-downloads]: https://poser.pugx.org/embed/embed/d/monthly

[link-packagist]: https://packagist.org/packages/embed/embed
