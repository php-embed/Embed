<?php
declare(strict_types = 1);

namespace Embed\Adapters\Twitter;

use Embed\OEmbed as Base;
use Psr\Http\Message\UriInterface;

/**
 * Twitter and X redirect logged-out visitors to /home, a login wall or the
 * x.com/x/migrate form, so the oEmbed lookup always uses the status URL
 * that was requested instead of the page where the redirects ended.
 */
class OEmbed extends Base
{
    const ENDPOINT = 'https://publish.twitter.com/oembed';

    private ?string $statusUrl = null;

    /**
     * @return array<string, mixed>
     */
    protected function fetchData(): array
    {
        $data = parent::fetchData();

        if ($data !== [] && $this->statusUrl !== null) {
            $data += ['url' => $this->statusUrl];
        }

        return $data;
    }

    protected function detectEndpoint(): ?UriInterface
    {
        $status = $this->extractor instanceof Extractor ? $this->extractor->getStatusUri() : null;

        if ($status === null) {
            return parent::detectEndpoint();
        }

        $this->statusUrl = (string) $status;

        return $this->extractor->getCrawler()
            ->createUri(self::ENDPOINT)
            ->withQuery(http_build_query($this->getOembedQueryParameters((string) $status)));
    }
}
