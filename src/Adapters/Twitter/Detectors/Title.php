<?php
declare(strict_types = 1);

namespace Embed\Adapters\Twitter\Detectors;

use Embed\Adapters\Twitter\Extractor;
use Embed\Detectors\Title as Detector;

/**
 * @extends Detector<\Embed\Adapters\Twitter\Extractor>
 */
class Title extends Detector
{
    public function detect(): ?string
    {
        $api = $this->extractor->getApi();
        $name = $api->str('includes', 'users', '0', 'name');

        if ($name !== null) {
            return "Tweet by $name";
        }

        // After a redirect to a login or migration page the HTML title is
        // not about the tweet, so use the oEmbed author instead.
        $status = $this->extractor->getStatusUri();
        $final = $this->extractor->getUri();
        if ($status !== null && strpos($final->getPath(), $status->getPath()) !== 0) {
            $author = $this->extractor->getOEmbed()->str('author_name');
            if ($author !== null) {
                return "Tweet by $author";
            }
        }

        return parent::detect();
    }
}
