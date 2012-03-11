<?php
require_once 'configuration.php';
require_once 'PHPUnit/Framework.php';
require_once '../main/Framework.php';

class CollectionTest extends PHPUnit_Framework_TestCase
{
    public static function setUpBeforeClass()
    {
        pg_query("DELETE FROM collection_domain");
        pg_query("DELETE FROM collection");

        $account = Account::login("searchzen.org", "test");
        $params = (object)array("accountId" => $account->id,
             "name" => "jacobs stuff",
             "startUrl" => "http://searchzen.org",
             "pageLimit" => 1500,
             "levelLimit" => 15);

        $collection = Collection::create($params);
        $collection->addDomain("searchzen.org");
    }

    public function testCollectionDomains()
    {
        $params = json_decode('{"id":"' . $this->getTestColId() . '"}');
        $collections = Collection::retrieve($params);
        print_r($collections);
        $this->assertEquals($collections[0]->domains[0]->domain, "searchzen.org");
    }

    public function testGetDomains()
    {
        $params = json_decode('{"collectionId":"' . $this->getTestColId() . '"}');
        $domains = CollectionDomain::retrieve($params);
        $this->assertEquals($domains[0]->domain, "searchzen.org");
    }

    private function getTestColId()
    {
        $res = pg_query("SELECT collection_id from collection where name='jacobs stuff'");
        $row = pg_fetch_array($res);
        $colid = $row['collection_id'];

        if ($colid == '') {
            die("missing test collection\r\n");
        }
        return $colid;
    }
}
