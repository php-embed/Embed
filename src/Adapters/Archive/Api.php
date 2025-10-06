<?php
declare(strict_types = 1);

namespace Embed\Adapters\Archive;

use Embed\HttpApiTrait;

class Api
{
    use HttpApiTrait;

    /**
     * @return array<string, mixed>
     */
    protected function fetchData(): array
    {
        $this->endpoint = $this->extractor->getUri()->withQuery('output=json');

        return $this->fetchJSON($this->endpoint);
    }
}
