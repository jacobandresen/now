<?php
require_once 'PHPUnit/Framework.php';
require_once '../main/Framework.php';

require_once 'AccountTest.php';
require_once 'CollectionTest.php';
require_once 'ControllerTest.php';
require_once 'CrawlerTest.php';
require_once 'IndexerTest.php';
require_once 'SearcherTest.php';

class BasicTestSuite extends PHPUnit_Framework_TestSuite
{
    public static function suite()
    {
        $suite = new PHPUnit_Framework_TestSuite();

        $suite->addTest(new AccountTest("testCreate"));
       // $suite->addTest(new AccountTest("testDelete"));
        $suite->addTest(new CollectionTest("testCollectionDomains"));
        $suite->addtest(new CollectionTest("testgetDomains"));
        $suite->addTest(new ControllerTest("testCollectionRetrieve"));
        $suite->addTest(new CrawlerTest("testCrawl"));
        $suite->addTest(new IndexerTest("testIndex"));
        $suite->addTest(new SearcherTest("testSearch"));

        return $suite;
    }
}
?>
