<?php
declare(strict_types = 1);

namespace Embed\Tests;

class PagesTest extends PagesTestCase
{
    /**
     * -1 = Read from cache (throws an exception if the cache doesn't exist)
     * 0 = Read from cache (generate the files the first time)
     * 1 = Read from net without override the cache files
     * 2 = Read from net and override the cache files
     */
    const CACHE = -1;

    /**
     * 0 = Do not override the fixtures
     * 1 = Override the fixtures
     */
    const FIXTURES = 0;

    public function urlDataProvider(): array
    {
        return [
            ['https://www.abanca.com/gl'],
            ['https://animoto.com/play/GjsJ1gu0WDRfr4pGw12xZQ'],
            ['http://live.amcharts.com/czNjJ'],
            ['https://www.aol.com/video/view/pile-of-recovering-foster-kittens-is-purrfect/595fe75985eb42109b69bedb/'],
            ['http://jeanjean.bandcamp.com/track/coquin-l-l-phant'],
            ['http://codepen.io/Zhouzi/pen/JoRazP'],
            ['http://www.dailymotion.com/video/xy0wd'],
            ['http://www.deviantart.com/art/Misty-510056679'],
            ['http://pachunka.deviantart.com/art/Cope-145564099'],
            ['http://www.hookem.com/story/texas-shortstop-joe-baker-arrested-public-intoxication/'],
            ['http://i.imgur.com/X6rkCc5.jpg'],
            ['https://infogr.am/7743c36a-f3ca-4465-9a80-a8abbd5d8dc4'],
            ['http://output.jsbin.com/vonesu/10'],
            ['http://jsfiddle.net/zhm5rjnz/'],
            ['https://www.kickstarter.com/projects/1452363698/good-seed-craft-veggie-burgers'],
            ['http://www.23hq.com/Zzleeper/photo/16600737'],
            ['https://500px.com/photo/138251239/taganay-park-by-daniel-kordan'],
            ['https://pastebin.com/d4biUtRm'],
            ['http://media.photobucket.com/user/Ignwar/media/Album%20Deserts/MoonriseMonumentValleyUtah.jpg.html?filters[term]=sunsets&filters[primary]=images'],
            ['http://www.politico.com/story/2013/12/presidents-barack-obama-george-w-bush-second-term-101314.html'],
            ['http://polldaddy.com/poll/7012505/'],
            ['https://www.reddit.com/r/investing/comments/7pfpeq/buffett_on_cyrptocurrencies_i_can_say_almost_with/'],
            ['http://www.scribd.com/doc/110799637/Synthesis-of-Knowledge-Effects-of-Fire-and-Thinning-Treatments-on-Understory-Vegetation-in-Dry-U-S-Forests'],
            ['http://www.spreaker.com/user/angelclark/angel-clark-ice-cream-tocos'],
            ['http://www.ted.com/talks/george_monbiot_for_more_wonder_rewild_the_world?language=en#t-689901'],
            ['http://he-who-photographs-rather-ok.tumblr.com/post/165326273724'],
            ['http://www.ustream.tv/channel/red-shoes-billiards-60803-camera-1'],
            ['http://www.viddler.com/v/bdce8c7'],
            ['http://www.wired.com/?p=2064839'],
            ['https://www.itmedia.co.jp/news/articles/2410/28/news159.html'],
            ['https://4pda.to/2022/12/04/406834/sostoyalsya_reliz_clown_of_duty_parodii_na_call_of_duty/'],
            // Bilibili and Youku snapshots were captured from a restricted network (a US
            // data center IP): Bilibili serves its error page and Youku blocks the request.
            ['https://www.bilibili.com/video/BV1GJ411x7h7'],
            ['https://v.youku.com/v_show/id_XNTk1MTU4MDQ0OA==.html'],
            ['https://music.163.com/song?id=186016'],
            ['https://music.163.com/#/album?id=18905'],
        ];
    }

    /**
     * @dataProvider urlDataProvider
     */
    public function testPages(string $url)
    {
        $this->assertEmbed($url);
    }

    public function testImageFile()
    {
        $this->assertEmbed('https://cdn2.thecatapi.com/images/cjd.jpg');
    }

    public function testAudioFile()
    {
        $this->assertEmbed('https://www.soundhelix.com/examples/mp3/SoundHelix-Song-1.mp3');
    }

    public function testVideoFile()
    {
        $this->assertEmbed('http://commondatastorage.googleapis.com/gtv-videos-bucket/sample/BigBuckBunny.mp4');
    }

    public function testSnipplr()
    {
        $this->assertEmbed('http://snipplr.com/view/72914/better-html-5-basic-starter-template');
    }

    public function testImageShack()
    {
        $this->assertEmbed('https://imageshack.com/i/ip7wO0v7j');
    }

    public function testIdeone()
    {
        $this->assertEmbed('https://ideone.com/Whjntg');
    }

    public function testCadenaSer()
    {
        $this->assertEmbed('http://play.cadenaser.com/audio/001RD010000004275766/');
    }

    public function testSlides()
    {
        $this->assertEmbed('http://slides.com/alexwalker/responsive-svg/');
    }

    public function testArchiveOrg()
    {
        $this->assertEmbed('https://archive.org/details/dn2015-0220_vid');
        $this->assertEmbed('https://archive.org/details/librivoxaudio');
    }

    public function testInstagram()
    {
        $this->assertEmbed('http://instagram.com/p/ySl7G9tO_q/');
    }

    public function testMeetup()
    {
        $this->assertEmbed('https://www.meetup.com/es/GPUL-Labs/events/248885422/');
    }

    public function testPinterest()
    {
        $this->assertEmbed('https://www.pinterest.com/pin/106890191127977979/');
    }

    public function testWikipedia()
    {
        $this->assertEmbed('https://en.wikipedia.org/wiki/Albert_Einstein');
    }

    public function testVimeo()
    {
        $this->assertEmbed('https://vimeo.com/235352744');
    }

    public function testWordPress()
    {
        $this->assertEmbed('http://wordpress.tv/2013/09/06/dave-ross-optimize-image-files-like-a-pro/');
    }

    public function testFlickr()
    {
        $this->assertEmbed('https://www.flickr.com/photos/desescribir/sets/72157650686499888');
        $this->assertEmbed('https://www.flickr.com/photos/desescribir');
    }

    public function testFacebook()
    {
        $this->assertEmbed('https://www.facebook.com/permalink.php?story_fbid=827163017327807&id=149460691764713');
        $this->assertEmbed('https://www.facebook.com/acolono/videos/10154107990797381/');
    }

    public function testGithub()
    {
        $this->assertEmbed('https://gist.github.com/oscarotero/7749998');
        $this->assertEmbed('https://github.com/benbalter/dc-wifi-social/blob/master/bars.geojson');
        $this->assertEmbed('https://github.com/skalnik/secret-bear-clip/blob/master/stl/clip.stl');
        $this->assertEmbed('https://github.com/oscarotero/Embed/blob/master/composer.json');
    }

    public function testGoogle()
    {
        $this->assertEmbed('https://www.google.es/maps/place/Tordoia,+A+Coru%C3%B1a/@43.0871207,-8.5710004,12z/data=!3m1!4b1!4m2!3m1!1s0xd2ef4006f1ef489:0x404f58273ca55a0');
        $this->assertEmbed('https://drive.google.com/file/d/0B2rwN8wAbVSWbmFJdUdnV2VSTTg/view');
        $this->assertEmbed('https://calendar.google.com/calendar/embed?src=spain__es%40holiday.calendar.google.com&ctz=Europe/Madrid');
    }

    public function testSoundCloud()
    {
        $this->assertEmbed('https://soundcloud.com/zedsdead/zeds-dead-twin-shadow-lost-you-feat-dangelo-lacy');
        $this->assertEmbed('https://soundcloud.com/bruceneilmusic');
        $this->assertEmbed('https://soundcloud.com/ididthat-1/lowe-ct-cape-times-world-cup');
    }

    public function testSpotify()
    {
        $this->assertEmbed('https://open.spotify.com/album/7s66wU1XJ2NsUuWM2NKiUV');
        $this->assertEmbed('https://play.spotify.com/album/7s66wU1XJ2NsUuWM2NKiUV');
    }

    public function testTwitch()
    {
        $this->assertEmbed('https://www.twitch.tv/videos/72749628');
        $this->assertEmbed('https://www.twitch.tv/videos/106400740');
        $this->assertEmbed('https://www.twitch.tv/twit');
    }

    public function testTwitter()
    {
        $this->assertEmbed('https://twitter.com/pepephone/status/436461658601713664');
        $this->assertEmbed('https://t.co/4DK3Jc2BTt');
    }

    public function testTikTok()
    {
        $this->assertEmbed('https://www.tiktok.com/@a3noticias/video/6806030056956251397');
    }

    public function testYoutube()
    {
        $this->assertEmbed('http://www.youtube.com/watch?v=eiHXASgRTcA');
        $this->assertEmbed('http://youtu.be/eiHXASgRTcA');
        $this->assertEmbed('https://www.youtube.com/playlist?list=PL4qTakKkQATKOyZPJG_cAMnRtF7fAIdST');
        $this->assertEmbed('https://www.youtube.com/channel/UCuZeHD5SGecQomz2pVDHGzg');
    }

    public function testBBCNews()
    {
        $this->assertEmbed('https://www.bbc.co.uk/news/uk-54222286');
    }
}
