<?php

use PHPUnit\Framework\TestCase;
use SearchRank\HttpClient;
use SearchRank\Google;

class GoogleTest extends TestCase implements HttpClient {
    function testRank() {
        $google = new Google($this);
        $rank = $google->rank('keyword', 'https://www.suzukikenichi.com');
        $this->assertSame(4, $rank);
        $rank = $google->rank('keyword', 'https://www.seojapan.com');
        $this->assertSame(5, $rank);
    }

    function testGetSerpUrl() {
        $google = new Google();
        $serp = $google->getSerpUrl('keyword', 1);
        $this->assertSame("https://www.google.com/search?q=keyword&start=0", $serp);
        $serp = $google->getSerpUrl('アイウエオ カキクケコ', 5);
        $this->assertSame("https://www.google.com/search?q=アイウエオ+カキクケコ&start=40", $serp);
    }

    function testGetSerpUrlThrowException() {
        $google = new Google();
        $this->expectException(SearchRank\Exception::class);
        $serp = $google->getSerpUrl('keyword', 0);
    }

    function testParseHtml() {
        $html = file_get_contents(__DIR__ . '/GoogleSerp.html');
        $google = new Google();
        $results = $google->parseHtml($html);
        $this->assertCount(10, $results);
        $this->assertSame('https://service.plan-b.co.jp/blog/seo/8806/', $results[0]);
    }

    function download(String $url): ?String {
        $html = file_get_contents(__DIR__ . '/GoogleSerp.html');
        return $html;
    }
}