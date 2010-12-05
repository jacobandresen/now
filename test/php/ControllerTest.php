<?php
require_once 'PHPUnit/Framework.php';
require_once '../../php/YASE/Framework.php';

class ControllerTest extends PHPUnit_Framework_TestCase
{
  public static function setUpBeforeClass()
  { 
    mysql_query("DELETE FROM collection") or die(mysql_error());

    $account = Account::login("pedant.dk", "test");
    $params = (object) array("parentId" => $account->id, "name" => "jacobs stuff", "startUrl" => "http://pedant.dk", "pageLimit" => 1500, "levelLimit" => 15);

    $collection = Collection::create($params);
    $collection->addDomain("pedant.dk");
  }  

  public function testCollectionRetrieve()
  {
    Account::generateToken("pedant.dk", "test");
    $client = new HTTPClient();
    $token = $client->get(YASE_WEB.'/token.php?username=pedant.dk&password=test');
    $json = $client->get(YASE_WEB.'/app.php?controller=Collection&token='.$token.'&action=retrieve&json={"id":"'.$this->getTestColid().'"}');
    $response = json_decode($json);
    $collections = $response->data;
    $this->assertEquals($collections[0]->domains[0]->name, "pedant.dk");
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
