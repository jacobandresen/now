<?php
require_once 'PHPUnit/Framework.php';
require_once '../php/Framework.php';

class CollectionTest extends PHPUnit_Framework_TestCase
{
  public static function setUpBeforeClass()
  { 
    mysql_query("DELETE FROM collection") or die(mysql_error());

    $account = Account::login("pedant.dk", "test");
    $params = (object) array("ownerId" => $account->id, "name" => "jacobs stuff", "startUrl" => "http://pedant.dk", "pageLimit" => 1500, "levelLimit" => 15);

    $collection = Collection::create($params);
    $collection->addDomain("pedant.dk");
  }  

 public function testCollectionDomains()
  {
    $params = json_decode('{"id":"'.$this->getTestColId().'"}');
    $collection =  Collection::retrieve($params);
    $this->assertEquals($collection->domains[0]->name, "pedant.dk");
  }

  public function testControllerRetrieve()
  {
    $client = new HTTPClient();
    $json = $client->get(YASE_WEB.'/app.php?controller=Collection&action=retrieve&json={"id":"'.$this->getTestColid().'"}');

    $response = json_decode($json);
    $collection = $response->data;
    $this->assertEquals($collection->domains[0]->name, "pedant.dk");
  }

  public function testGetDomains()
  {
    $params = json_decode('{"collectionId":"'.$this->getTestColId().'"}');
    $domains = Domain::retrieve($params);
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
