<?php
declare(strict_types = 1);

namespace Embed\Adapters\Gist;

use Embed\HttpApiTrait;

class Api
{
    use HttpApiTrait;

    /**
     * @return array<string, mixed>
     */
    protected function fetchData(): array
    {
        $uri = $this->extractor->getUri();
        $this->endpoint = $uri->withPath($uri->getPath().'.json');

        return $this->fetchJSON($this->endpoint);
    }
}
