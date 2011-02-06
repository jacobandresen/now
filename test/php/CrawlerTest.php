<?php
require_once 'PHPUnit/Autoload.php';
require_once 'YASE/Framework.php';

class CrawlerTest extends PHPUnit_Framework_TestCase
{
    public function testCrawl()
    {
        $account = Account::login("pedant.dk", "test");
        $c = $account->collections[0];

        $crawler = new Crawler($c);
        $crawler->pageLimit = 10;
        $crawler->start();
    }
}