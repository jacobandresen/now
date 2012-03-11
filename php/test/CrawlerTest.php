<?php
require_once 'configuration.php';
require_once 'PHPUnit/Framework.php';
require_once '../main/Framework.php';

class CrawlerTest extends PHPUnit_Framework_TestCase
{
    public function testCrawl()
    {
        $account = Account::login("searchzen.org", "test");
        $c = $account->collections[0];

        $crawler = new Crawler($c);
        $crawler->pageLimit = 10;
        $crawler->start();
    }
}
