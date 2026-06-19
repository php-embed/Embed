<?php
declare(strict_types = 1);

namespace Embed\Tests;

use Embed\Embed;
use PHPUnit\Framework\TestCase;

class MultipleRequestsTest extends TestCase
{
    public function testParallel()
    {
        $embed = new Embed();
        $infos = $embed->getMulti(
            'https://oscarotero.com',
            'https://github.com/oscarotero',
            'https://twitter.com/misteroom',
        );

        $this->assertCount(3, $infos);
        $this->assertEquals('https://oscarotero.com/', (string) $infos[0]->url);
        $this->assertEquals('Óscar Otero - Digital designer and developer', $infos[0]->title);

        $this->assertEquals('https://github.com/oscarotero', (string) $infos[1]->url);
        $this->assertEquals('oscarotero - Overview', $infos[1]->title);

        $this->assertEquals('https://s.com/misteroom', (string) $infos[2]->url);
        $this->assertEquals('en', $infos[2]->language);
    }
}
