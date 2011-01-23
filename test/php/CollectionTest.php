<?php
require_once 'PHPUnit/Autoload.php';
require_once 'YASE/Framework.php';

class CollectionTest extends PHPUnit_Framework_TestCase
{
  public static function setUpBeforeClass()
  { 
    mysql_query("DELETE FROM collection") or die(mysql_error());

    $account = Account::login("pedant.dk", "test");
    $params = (object) array("parentId" => $account->id, "name" => "jacobs stuff", "startUrl" => "http://pedant.dk", "pageLimit" => 1500, "levelLimit" => 15);

    $collection = Collection::create($params);
    $collection->addDomain("pedant.dk");
  }  

  public function testCollectionDomains()
  {
    $params = json_decode('{"id":"'.$this->getTestColId().'"}');
    $collections =  Collection::retrieve($params);
    $this->assertEquals($collections[0]->domains[0]->name, "pedant.dk");
  }

  public function testGetDomains()
  {
    $params = json_decode('{"parentId":"'.$this->getTestColId().'"}');
    $domains = Domain::retrieve($params);
    $this->assertEquals($domains[0]->name, "pedant.dk");
  }
  
  private function getTestColId()
  {
    $res = mysql_query("SELECT id from collection where name='jacobs stuff'");
    $row = mysql_fetch_array($res);   
    $colid = $row['id']; 

    if ($colid=='') {
      die( "missing test collection\r\n" );
    }
    return $colid;
  }

}
