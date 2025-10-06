<?php
declare(strict_types = 1);

namespace Embed;

use DateTime;
use DomainException;
use Embed\Detectors\AuthorName;
use Embed\Detectors\AuthorUrl;
use Embed\Detectors\Cms;
use Embed\Detectors\Code;
use Embed\Detectors\Description;
use Embed\Detectors\Detector;
use Embed\Detectors\Favicon;
use Embed\Detectors\Feeds;
use Embed\Detectors\Icon;
use Embed\Detectors\Image;
use Embed\Detectors\Keywords;
use Embed\Detectors\Language;
use Embed\Detectors\Languages;
use Embed\Detectors\License;
use Embed\Detectors\ProviderName;
use Embed\Detectors\ProviderUrl;
use Embed\Detectors\PublishedTime;
use Embed\Detectors\Redirect;
use Embed\Detectors\Title;
use Embed\Detectors\Url;
use Embed\Http\Crawler;
use InvalidArgumentException;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\UriInterface;

/**
 * Class to extract the info
 *
 * @property string|null          $authorName
 * @property UriInterface|null    $authorUrl
 * @property string|null          $cms
 * @property EmbedCode|null       $code
 * @property string|null          $description
 * @property UriInterface         $favicon
 * @property array|UriInterface[] $feeds
 * @property UriInterface|null    $icon
 * @property UriInterface|null    $image
 * @property array|string[]       $keywords
 * @property string|null          $language
 * @property array|UriInterface[] $languages
 * @property string|null          $license
 * @property string               $providerName
 * @property UriInterface         $providerUrl
 * @property DateTime|null        $publishedTime
 * @property UriInterface|null    $redirect
 * @property string|null          $title
 * @property UriInterface         $url
 */
class Extractor
{
    private RequestInterface $request;
    private ResponseInterface $response;
    private UriInterface $uri;
    private Crawler $crawler;

    protected Document $document;
    protected OEmbed $oembed;
    protected LinkedData $linkedData;
    protected Metas $metas;

    /** @var array<string, mixed> */
    private array $settings = [];
    /** @var array<string, Detectors\Detector> */
    private array $customDetectors = [];

    protected AuthorName $authorName;
    protected AuthorUrl $authorUrl;
    protected Cms $cms;
    protected Code $code;
    protected Description $description;
    protected Favicon $favicon;
    protected Feeds $feeds;
    protected Icon $icon;
    protected Image $image;
    protected Keywords $keywords;
    protected Language $language;
    protected Languages $languages;
    protected License $license;
    protected ProviderName $providerName;
    protected ProviderUrl $providerUrl;
    protected PublishedTime $publishedTime;
    protected Redirect $redirect;
    protected Title $title;
    protected Url $url;

    public function __construct(UriInterface $uri, RequestInterface $request, ResponseInterface $response, Crawler $crawler)
    {
        $this->uri = $uri;
        $this->request = $request;
        $this->response = $response;
        $this->crawler = $crawler;

        //APIs
        $this->document = new Document($this);
        $this->oembed = new OEmbed($this);
        $this->linkedData = new LinkedData($this);
        $this->metas = new Metas($this);

        //Detectors
        $this->authorName = new AuthorName($this);
        $this->authorUrl = new AuthorUrl($this);
        $this->cms = new Cms($this);
        $this->code = new Code($this);
        $this->description = new Description($this);
        $this->favicon = new Favicon($this);
        $this->feeds = new Feeds($this);
        $this->icon = new Icon($this);
        $this->image = new Image($this);
        $this->keywords = new Keywords($this);
        $this->language = new Language($this);
        $this->languages = new Languages($this);
        $this->license = new License($this);
        $this->providerName = new ProviderName($this);
        $this->providerUrl = new ProviderUrl($this);
        $this->publishedTime = new PublishedTime($this);
        $this->redirect = new Redirect($this);
        $this->title = new Title($this);
        $this->url = new Url($this);
    }

    /**
     * @return mixed
     */
    public function __get(string $name)
    {
        $detector = $this->customDetectors[$name] ?? null;

        if ($detector === null && property_exists($this, $name)) {
            /** @var mixed $property */
            /** @phpstan-ignore property.dynamicName */
            $property = (fn($n) => $this->$n)($name);
            if ($property instanceof Detector) {
                $detector = $property;
            }
        }

        if ($detector === null) {
            throw new DomainException(sprintf('Invalid key "%s". No detector found for this value', $name));
        }

        return $detector->get();
    }

    /**
     * @return array<string, Detector>
     */
    public function createCustomDetectors(): array
    {
        return [];
    }

    public function addDetector(string $name, Detector $detector): void
    {
        $this->customDetectors[$name] = $detector;
    }

    /**
     * @param array<string, mixed> $settings
     */
    public function setSettings(array $settings): void
    {
        $this->settings = $settings;
    }

    /**
     * @return array<string, mixed>
     */
    public function getSettings(): array
    {
        return $this->settings;
    }

    /**
     * @return mixed
     */
    public function getSetting(string $key)
    {
        return $this->settings[$key] ?? null;
    }

    public function getDocument(): Document
    {
        return $this->document;
    }

    public function getOEmbed(): OEmbed
    {
        return $this->oembed;
    }

    public function getLinkedData(): LinkedData
    {
        return $this->linkedData;
    }

    public function getMetas(): Metas
    {
        return $this->metas;
    }

    public function getRequest(): RequestInterface
    {
        return $this->request;
    }

    public function getResponse(): ResponseInterface
    {
        return $this->response;
    }

    public function getUri(): UriInterface
    {
        return $this->uri;
    }

    /**
     * @param UriInterface|string $uri
     */
    public function resolveUri($uri): UriInterface
    {
        if (is_string($uri)) {
            if (!isHttp($uri)) {
                throw new InvalidArgumentException(sprintf('Uri string must use http or https scheme (%s)', $uri));
            }

            $uri = $this->crawler->createUri($uri);
        }

        return resolveUri($this->uri, $uri);
    }

    public function getCrawler(): Crawler
    {
        return $this->crawler;
    }
}
