<?php
require_once 'configuration.php';
require_once 'PHPUnit/Framework/TestCase.php';
require_once '../main/Framework.php';

class SearcherTest extends PHPUnit_Framework_TestCase
{
    public function testSearch () {
        $account = Account::login("searchzen.org", "test");
        $collection = $account->collections[0];
        $searcher = new Searcher($collection);
        $results = $searcher->search("Jacob", 0);
    }
}
?>
