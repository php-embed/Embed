<?php
declare(strict_types = 1);

namespace Embed\Tests;

use Embed\EmbedCode;
use PHPUnit\Framework\TestCase;

class EmbedCodeTest extends TestCase
{
    public function testRatioCalculationNormal()
    {
        // Normal case: width=380, height=120
        $code = new EmbedCode('<iframe></iframe>', 380, 120);
        $this->assertEqualsWithDelta(31.579, $code->ratio, 0.001);
    }

    public function testRatioCalculationWithNullWidth()
    {
        // width=null case
        $code = new EmbedCode('<iframe></iframe>', null, 400);
        $this->assertNull($code->ratio);
    }

    public function testRatioCalculationWithZeroWidth()
    {
        // width=0 case (prevents division-by-zero)
        $code = new EmbedCode('<iframe></iframe>', 0, 400);
        $this->assertNull($code->ratio);
    }

    public function testRatioCalculationWithNullHeight()
    {
        // height=null case
        $code = new EmbedCode('<iframe></iframe>', 400, null);
        $this->assertNull($code->ratio);
    }

    public function testRatioCalculationWithZeroHeight()
    {
        // height=0 case (prevents meaningless ratio calculation)
        $code = new EmbedCode('<iframe></iframe>', 400, 0);
        $this->assertNull($code->ratio);
    }

    public function testRatioCalculationWithBothZero()
    {
        // width=0, height=0 case (prevents division-by-zero)
        $code = new EmbedCode('<iframe></iframe>', 0, 0);
        $this->assertNull($code->ratio);
    }

    public function testRatioCalculationWithBothNull()
    {
        // width=null, height=null case
        $code = new EmbedCode('<iframe></iframe>', null, null);
        $this->assertNull($code->ratio);
    }

    public function testJsonSerialize()
    {
        $code = new EmbedCode('<div>test</div>', 640, 480);
        $json = $code->jsonSerialize();

        $this->assertEquals('<div>test</div>', $json['html']);
        $this->assertEquals(640, $json['width']);
        $this->assertEquals(480, $json['height']);
        $this->assertEqualsWithDelta(75.0, $json['ratio'], 0.001);
    }

    public function testToString()
    {
        $html = '<iframe src="https://example.com"></iframe>';
        $code = new EmbedCode($html, 640, 480);

        $this->assertEquals($html, (string) $code);
    }

    public function testHtmlOnlyConstruction()
    {
        // Construction with HTML only (width/height are null)
        $code = new EmbedCode('<p>content</p>');

        $this->assertEquals('<p>content</p>', $code->html);
        $this->assertNull($code->width);
        $this->assertNull($code->height);
        $this->assertNull($code->ratio);
    }
}
