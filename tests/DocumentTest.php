<?php
declare(strict_types = 1);

namespace Embed\Tests;

use Embed\Embed;
use Embed\Http\Crawler;
use Embed\Http\UrlPolicy;
use PHPUnit\Framework\TestCase;

class DocumentTest extends TestCase
{
    private static Embed $embed;

    private static function getEmbed(): Embed
    {
        if (isset(self::$embed)) {
            return self::$embed;
        }

        $dispatcher = new FileClient(__DIR__.'/cache');
        $dispatcher->setMode(0);
        $crawler = new Crawler($dispatcher);
        $crawler->setUrlPolicy(UrlPolicy::default()->withResolver(static function (string $host): array {
            return ['8.8.8.8'];
        }));

        return self::$embed = new Embed($crawler);
    }

    public function testSelectors()
    {
        $extractor = self::getEmbed()->get('http://www.wired.com/?p=2064839');
        $document = $extractor->getDocument();

        $expected = 3;

        $this->assertCount($expected, $document->select('.//p')->nodes());
        $this->assertCount($expected, $document->selectCss('p')->nodes());

        $document->remove('.//p');

        $this->assertCount(0, $document->select('.//p')->nodes());
        $this->assertCount(0, $document->selectCss('p')->nodes());
    }

    public function testMultipleSelectors()
    {
        $extractor = self::getEmbed()->get('https://css-tricks.com/css-scrollbar-with-progress-meter/');
        $document = $extractor->getDocument();

        $expected = 113;

        $this->assertCount($expected, $document->selectCss('[aria-hidden],[hidden],meta,style,canvas,svg,form,script,template,link,.hidden')->nodes());
        $this->assertCount($expected, $document->select('.//*[@aria-hidden]|.//*[@hidden]|.//meta|.//style|.//canvas|.//svg|.//form|.//script|.//template|.//link|.//*[contains(concat(" ",normalize-space(@class)," ")," hidden ")]')->nodes());

        $document->removeCss('[aria-hidden],[hidden],meta,style,canvas,svg,form,script,template,link,.hidden');

        $this->assertCount(0, $document->selectCss('[aria-hidden],[hidden],meta,style,canvas,svg,form,script,template,link,.hidden')->nodes());
        $this->assertCount(0, $document->select('.//*[@aria-hidden]|.//*[@hidden]|.//meta|.//style|.//canvas|.//svg|.//form|.//script|.//template|.//link|.//*[contains(concat(" ",normalize-space(@class)," ")," hidden ")]')->nodes());
    }
}
