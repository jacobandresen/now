<?php
require_once 'configuration.php';
require_once 'PHPUnit/Autoload.php';
require_once 'YASE/Framework.php';

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
