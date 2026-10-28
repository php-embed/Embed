<?php
declare(strict_types = 1);

namespace Embed\Tests;

use Embed\Embed;
use Embed\Http\Crawler;
use Embed\Http\UrlPolicy;
use Nyholm\Psr7\Factory\Psr17Factory;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * Bilibili, Youku and NetEase Music adapters [#243].
 *
 * The player iframes are built from the URL alone, so these tests use
 * offline pages. The real pages could only be captured from a restricted
 * network (a US data center IP), where Bilibili serves its error page and
 * Youku blocks the request, so their real metadata is not covered here.
 */
class ChineseProvidersTest extends TestCase
{
    private const BILIBILI_ERROR = '<!DOCTYPE html><html><head><title>出错啦! - bilibili.com</title>'
        .'<meta name="description" content="bilibili是国内知名的视频弹幕网站"></head><body></body></html>';

    private RecordingClient $client;

    public function testBilibiliBvidBuildsPlayerIframe(): void
    {
        $info = $this->embed(['/video/BV1GJ411x7h7' => self::BILIBILI_ERROR])
            ->get('https://www.bilibili.com/video/BV1GJ411x7h7/?spm_id_from=333.1007');

        $this->assertNotNull($info->code);
        $this->assertSame(
            '<iframe src="https://player.bilibili.com/player.html?bvid=BV1GJ411x7h7&amp;autoplay=0" frameborder="0" allowfullscreen="true" scrolling="no" width="640" height="360"></iframe>',
            $info->code->html
        );
        $this->assertSame(640, $info->code->width);
        $this->assertSame(360, $info->code->height);
    }

    public function testBilibiliAidBuildsPlayerIframe(): void
    {
        $info = $this->embed(['/video/av170001' => self::BILIBILI_ERROR])
            ->get('https://www.bilibili.com/video/av170001');

        $this->assertNotNull($info->code);
        $this->assertStringContainsString('player.html?aid=170001&amp;autoplay=0', $info->code->html);
    }

    public function testBilibiliErrorPageHasNoMetadata(): void
    {
        $info = $this->embed(['/video/BV1GJ411x7h7' => self::BILIBILI_ERROR])
            ->get('https://www.bilibili.com/video/BV1GJ411x7h7');

        $this->assertNull($info->title);
        $this->assertNull($info->description);
        $this->assertNull($info->image);
    }

    public function testBilibiliVideoPageKeepsMetadata(): void
    {
        $info = $this->embed([
            '/video/BV1GJ411x7h7' => $this->page(
                '<meta property="og:title" content="Video title">'
                .'<meta property="og:image" content="https://i0.hdslb.com/bfs/archive/cover.jpg">'
            ),
        ])->get('https://www.bilibili.com/video/BV1GJ411x7h7');

        $this->assertSame('Video title', $info->title);
        $this->assertSame('https://i0.hdslb.com/bfs/archive/cover.jpg', (string) $info->image);
    }

    public function testBilibiliOtherPageHasNoPlayer(): void
    {
        $info = $this->embed(['/' => $this->page('')])->get('https://www.bilibili.com/');

        $this->assertNull($info->code);
    }

    public function testYoukuBuildsPlayerIframe(): void
    {
        $info = $this->embed(['/v_show/id_XNTk1MTU4MDQ0OA==.html' => $this->page('')])
            ->get('https://v.youku.com/v_show/id_XNTk1MTU4MDQ0OA==.html?spm=a2hja.12701474');

        $this->assertNotNull($info->code);
        $this->assertSame(
            '<iframe src="https://player.youku.com/embed/XNTk1MTU4MDQ0OA%3D%3D" frameborder="0" allowfullscreen="true" scrolling="no" width="640" height="360"></iframe>',
            $info->code->html
        );
        $this->assertSame(640, $info->code->width);
        $this->assertSame(360, $info->code->height);
    }

    public function testYoukuBlockedRequestStillHasPlayer(): void
    {
        $info = $this->embed([])->get('https://v.youku.com/v_show/id_XNTk1MTU4MDQ0OA==.html');

        $this->assertNotNull($info->code);
        $this->assertStringContainsString('player.youku.com/embed/', $info->code->html);
    }

    /**
     * @return array<array{string, int, string}>
     */
    public function netEaseUrlProvider(): array
    {
        return [
            ['https://music.163.com/song?id=186016', 2, '186016'],
            ['https://music.163.com/#/song?id=186016', 2, '186016'],
            ['https://music.163.com/#/album?id=18905', 1, '18905'],
            ['https://music.163.com/#/playlist?id=3778678', 0, '3778678'],
            ['https://music.163.com/#/m/song?id=186016', 2, '186016'],
        ];
    }

    /**
     * @dataProvider netEaseUrlProvider
     */
    public function testNetEaseBuildsPlayerIframe(string $url, int $type, string $id): void
    {
        $info = $this->embed([
            '/song' => $this->page(''),
            '/album' => $this->page(''),
            '/playlist' => $this->page(''),
        ])->get($url);

        $this->assertNotNull($info->code);
        $this->assertSame(
            "<iframe src=\"https://music.163.com/outchain/player?type={$type}&amp;id={$id}&amp;auto=0&amp;height=66\" frameborder=\"0\" scrolling=\"no\" width=\"330\" height=\"86\"></iframe>",
            $info->code->html
        );
        $this->assertSame(330, $info->code->width);
        $this->assertSame(86, $info->code->height);
    }

    public function testNetEaseFragmentRouteIsRequestedAsPath(): void
    {
        $info = $this->embed(['/song' => $this->page('')])->get('https://music.163.com/#/song?id=186016');
        $urls = array_map(static fn (RequestInterface $request): string => (string) $request->getUri(), $this->client->requests);

        $this->assertSame(['https://music.163.com/song?id=186016'], $urls);
        $this->assertSame('https://music.163.com/song?id=186016', (string) $info->url);
    }

    public function testNetEaseAuthorFromDescription(): void
    {
        $info = $this->embed([
            '/song' => $this->page('<meta name="description" content="晴天，由 周杰伦 演唱，收录于《叶惠美》专辑中。">'),
        ])->get('https://music.163.com/song?id=186016');

        $this->assertSame('周杰伦', $info->authorName);
    }

    public function testNetEaseOtherPageHasNoPlayer(): void
    {
        $info = $this->embed(['/' => $this->page('')])->get('https://music.163.com/');

        $this->assertNull($info->code);
    }

    private function page(string $head): string
    {
        return '<!DOCTYPE html><html><head><title>Page title</title>'.$head.'</head><body></body></html>';
    }

    /**
     * @param array<string, string> $routes path => body
     */
    private function embed(array $routes): Embed
    {
        $this->client = $client = new RecordingClient(static function (RequestInterface $request) use ($routes): ResponseInterface {
            $factory = new Psr17Factory();
            $body = $routes[$request->getUri()->getPath()] ?? null;

            if ($body === null) {
                return $factory->createResponse(403)->withBody($factory->createStream(''));
            }

            return $factory->createResponse(200)
                ->withHeader('Content-Type', 'text/html')
                ->withBody($factory->createStream($body));
        });

        $crawler = new Crawler($client);
        $crawler->setUrlPolicy(UrlPolicy::default()->withResolver(static function (string $host): array {
            return ['1.1.1.1'];
        }));

        return new Embed($crawler);
    }
}
