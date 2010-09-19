<?php
require_once 'PHPUnit/Framework.php';
require_once '../../php/Framework.php';

class SearcherTest extends PHPUnit_Framework_TestCase
{
  public function testSearch()
  {
    $account = Account::login("pedant.dk", "test");

    $collection = $account->collections[0];

    $searcher = new Searcher($collection);
    $result = $searcher->search("ExtJS", 0);

    $this->assertContains("ExtJS", $result[1]->content);
  }

}
?>