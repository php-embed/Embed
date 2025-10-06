<?php
declare(strict_types = 1);

namespace Embed\Detectors;

use DateTime;

class PublishedTime extends Detector
{
    public function detect(): ?DateTime
    {
        $oembed = $this->extractor->getOEmbed();
        $metas = $this->extractor->getMetas();
        $ld = $this->extractor->getLinkedData();

        $result = $oembed->time('pubdate');
        if ($result !== null) {
            return $result;
        }

        $result = $metas->time(
            'article:published_time',
            'created',
            'date',
            'datepublished',
            'music:release_date',
            'video:release_date',
            'newsrepublic:publish_date'
        );
        if ($result !== null) {
            return $result;
        }

        $result = $ld->time(
            'pagePublished',
            'datePublished'
        );
        if ($result !== null) {
            return $result;
        }

        $result = $this->detectFromPath();
        if ($result !== null) {
            return $result;
        }

        return $metas->time(
            'pagerender',
            'pub_date',
            'publication-date',
            'lp.article:published_time',
            'lp.article:modified_time',
            'publish-date',
            'rc.datecreation',
            'timestamp',
            'sailthru.date',
            'article:modified_time',
            'dcterms.date'
        );
    }

    /**
     * Some sites using WordPress have the published time in the url
     * For example: mysite.com/2020/05/19/post-title
     */
    private function detectFromPath(): ?DateTime
    {
        $path = $this->extractor->getUri()->getPath();

        if (preg_match('#/(19|20)\d{2}/[0-1]?\d/[0-3]?\d/#', $path, $matches) === 1) {
            $date = date_create_from_format('/Y/m/d/', $matches[0]);
            return $date !== false ? $date : null;
        }

        return null;
    }
}
