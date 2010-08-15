<?php
require_once 'PHPUnit/Framework.php';
require_once '../php/Framework.php';

class CrawlerTest extends PHPUnit_Framework_TestCase
{
  public function testCrawl()
  {
    $account = Account::login("jacob", "jacob");
    $c = $account->collections[0];
    
    $crawler =  new Crawler($c->id);
    $crawler->start();
  }

}
