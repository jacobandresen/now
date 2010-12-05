<?php
require_once 'PHPUnit/Framework.php';
require_once '../../php/YASE/Framework.php';

class IndexerTest extends PHPUnit_Framework_TestCase
{
  public function testIndex() 
  {
    $account = Account::login("pedant.dk", "test");
    $c = $account->collections[0];

    $indexer = new Indexer($c);
    $indexer->start();
  }

}
?>
