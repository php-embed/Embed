<?php
declare(strict_types = 1);

namespace Embed\Adapters\Kick;

use Embed\HttpApiTrait;

/**
 * Public Kick API data for a VOD page, which has no metadata in its html.
 */
class Api
{
    use HttpApiTrait;

    /**
     * @return array<string, mixed>
     */
    protected function fetchData(): array
    {
        $video = Extractor::parseVideoPath($this->extractor->getUri()->getPath());

        if ($video === null) {
            return [];
        }

        [$channel, $uuid] = $video;
        $crawler = $this->extractor->getCrawler();

        $this->endpoint = $crawler->createUri("https://kick.com/api/v1/video/{$uuid}");
        $data = $this->fetchJSON($this->endpoint);

        // The single video endpoint does not always include a thumbnail,
        // but the channel video list does.
        $list = $this->fetchJSON($crawler->createUri('https://kick.com/api/v2/channels/'.rawurlencode($channel).'/videos'));

        foreach ($list as $item) {
            if (is_array($item) && is_array($item['video'] ?? null) && ($item['video']['uuid'] ?? null) === $uuid) {
                $data['list_item'] = $item;
                break;
            }
        }

        return $data;
    }
}
