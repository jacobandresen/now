<?php
require_once 'configuration.php';
require_once 'PHPUnit/Framework/TestCase.php';
require_once '../main/Framework.php';

class IndexerTest extends PHPUnit_Framework_TestCase
{
    public function testIndex() {
        $account = Account::login("searchzen.org", "test");
        $c = $account->collections[0];

        $indexer = new Indexer($c);
        $indexer->start();
    }
}
?>
