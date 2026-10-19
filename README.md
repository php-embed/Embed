> [!IMPORTANT]
>
> # Searching for maintainer
> After several years working on Embed, I don't have the time or motivation to continue maintaining this project. I rarely write PHP code and am not aware of the latest features of PHP. If anyone wants to continue maintaining and evolving this library, please open an issue or contact me.
> 
> Meanwhile, I'll continue accepting PR from the community (I don't want this project to die), but won't be actively working on improving it.
> Thanks!


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
    'max_redirs' => 3,               // redirects to follow; a negative value means up to 50
    'connect_timeout' => 2,          // per connection, see CURLOPT_CONNECTTIMEOUT
    'timeout' => 2,                  // total seconds for the request, including redirects
    'ssl_verify_host' => 2,          // 0 disables hostname checks; any other value enables them
    'ssl_verify_peer' => 1,          // falsy disables certificate checks; any other value enables them
    'follow_location' => true,       // false returns the 3xx; each hop is checked
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

## Security / SSRF protection

The default HTTP client refuses to connect to non-public addresses. That covers the URL you pass to `get()` / `getMulti()` and every later request Embed makes itself (redirects, oEmbed endpoints declared by the page, adapter APIs, and meta-refresh targets). A hostname is allowed only when every IPv4 address it resolves to is public. Loopback, private, link-local, CGNAT, documentation, and multicast ranges are rejected. IPv4-mapped IPv6, NAT64, and 6to4 are rejected when the embedded IPv4 address is not public. The Teredo prefix `2001::/32` is rejected as a whole, not by reading an address out of it. Only `http` and `https` are allowed, and redirects are followed inside Embed so each hop is checked the same way.

`follow_location` set to false still returns the 3xx response. `max_redirs` is still how many redirects are followed (10 by default). A negative value, which curl treated as unlimited, follows at most 50. `timeout` is the total time for the request and its redirects; `connect_timeout` is still per connection. `ssl_verify_host` of `0` disables hostname checks, and any other value enables them, as curl does when it receives `1` or `true`. `ssl_verify_peer` is off only for a falsy value (`0`, `false`, `'0'`, `''`); `1`, `2`, `'true'` and `'yes'` keep certificate checks on. Reaching the redirect limit still fails with curl error 47. The message is `Number of redirects hit maximum amount`.

This is a behavior change: a URL that used to be fetched from an intranet now throws `Embed\Http\BlockedRequestException`. To opt back in:

```php
use Embed\Http\UrlPolicy;

$embed->getCrawler()->setUrlPolicy(
    UrlPolicy::default()->allowPrivateNetworks()
);
```

You can allow specific names without opening every private network. `*.corp` matches any host that ends in `.corp`. Allowing one resolved address does not allow the other addresses returned for that name:

```php
$embed->getCrawler()->setUrlPolicy(
    UrlPolicy::default()->withAllowedHosts('wiki.corp', '*.corp')
);
```

`withAllowedPorts([80, 443])` is optional. When it is set, an omitted port counts as 80 or 443. Internationalized host names are converted with `idn_to_ascii()`, which needs the `intl` extension; without it those hosts are rejected. The built-in client pins the punycode form, because that is the name libcurl looks up.

Values such as `image` or `favicon` are URLs extracted from the page. Embed does not download them, so it does not apply this policy to them. Check one before you fetch it yourself:

```php
$policy = $embed->getCrawler()->getUrlPolicy();
$image = $info->image;

if ($image !== null) {
    $policy->validate($embed->getCrawler()->createRequest('GET', (string) $image));
}
```

A page-declared oEmbed endpoint, adapter API, or meta-refresh target that the policy rejects is skipped, and extraction continues with the rest of the page. The URL passed to `get()` or `getMulti()` is not skipped: that request throws. In `getMulti()`, one rejected URL rejects the whole batch before any request is sent. A redirect discovered after the batch has started is checked on the same thread, including its DNS lookup, so the other transfers in that batch wait until the check returns.

`Crawler::setUrlPolicy()` copies the policy onto the client only when that client is exactly `Embed\Http\CurlClient`. A decorator or other wrapper around `CurlClient` is not updated. Call `setUrlPolicy()` on the inner client as well. Otherwise the wrapper is pre-checked with the crawler's policy, and the inner client keeps the policy it already had.

### Custom PSR-18 clients

If you pass your own PSR-18 client, Embed validates the URI before calling it and does not validate again inside that client. Two things are then outside Embed's control:

* DNS can change between the check and the connection (rebinding).
* Redirects followed by the client are not checked, unless the client gives you a hook.

The built-in curl client pins the validated addresses with `CURLOPT_RESOLVE` and follows redirects itself, so those gaps do not apply to it. With Guzzle you can repeat the check on each redirect:

```php
use Embed\Http\UrlPolicy;
use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\UriInterface;

$policy = UrlPolicy::default();
$stack = HandlerStack::create();
$stack->push(Middleware::mapRequest(function (RequestInterface $request) use ($policy) {
    $policy->validate($request);

    return $request;
}));

$client = new Client([
    'handler' => $stack,
    'allow_redirects' => [
        'on_redirect' => function (RequestInterface $request, ResponseInterface $response, UriInterface $uri) use ($policy): void {
            $policy->validate($request->withUri($uri));
        },
    ],
]);
```

Even that hook cannot see the address the client actually connected to. Prefer the built-in client when the pages are untrusted.

### Proxies

If `http_proxy`, `https_proxy`, or `all_proxy` is set, curl connects to the proxy and the proxy resolves the origin. Embed still validates each URL before requesting it, and still checks redirects, but it cannot pin DNS or verify the address the proxy used. Point those variables only at a proxy you trust.

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
