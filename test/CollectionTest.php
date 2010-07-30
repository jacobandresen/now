<?php
require_once 'PHPUnit/Framework.php';
require_once '../php/Framework.php';

class CollectionTest extends PHPUnit_Framework_TestCase
{
  public function testAdd()
  { 
    $account = Account::login("jacob", "jacob");
    mysql_query("DELETE FROM collection where owner_id=".$account->id) or die(mysql_error());

    $params = json_decode('{"ownerId":"'.$account->id.'","name":"jacobs stuff","pageLimit":1500,"levelLimit":15}');

    $collection = Collection::create($params);
    $collection->addDomain("pedant.dk");
    $collection->addDomain("searchzen.org");

    $this->assertEquals($collection->name, "jacobs stuff");
    $this->assertEquals($collection->domains[0], "pedant.dk");
    $this->assertEquals($collection->domains[1], "searchzen.org");
  }  

  public function testDelete()
  {
    $account = Account::login("jacob", "jacob");
    Account::delete($account->collections[0]->id);
  }

}
