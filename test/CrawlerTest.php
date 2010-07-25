<?php
require_once 'PHPUnit/Framework.php';
require_once '../php/Framework.php';

class CrawlerTest extends PHPUnit_Framework_TestCase
{
  public void testCrawl()
  {
    $account = Account::login("jacob", "jacob");
    $c = $account->collections[0];
    $c->start();
  }

}
