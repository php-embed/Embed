<?php
declare(strict_types = 1);

namespace Embed\Tests;

use Embed\Embed;
use Embed\Http\Crawler;
use Embed\Http\UrlPolicy;
use PHPUnit\Framework\TestCase;

class MultipleRequestsTest extends TestCase
{
    public function testParallel()
    {
        $dispatcher = new FileClient(__DIR__.'/cache');
        $dispatcher->setMode(-1);
        $crawler = new Crawler($dispatcher);
        // Some resolvers answer public names with 198.18.0.1, which the policy
        // rejects. Cached responses must not depend on that lookup.
        $crawler->setUrlPolicy(UrlPolicy::default()->withResolver(static function (string $host): array {
            return ['8.8.8.8'];
        }));
        $embed = new Embed($crawler);
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

        $this->assertEquals('https://x.com/misteroom', (string) $infos[2]->url);
        $this->assertEquals('en', $infos[2]->language);
    }
}
