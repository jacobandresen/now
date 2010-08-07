<?php
require_once 'PHPUnit/Framework.php';
require_once '../php/Framework.php';

class CollectionTest extends PHPUnit_Framework_TestCase
{
  public function testAdd()
  { 
    mysql_query("DELETE FROM collection") or die(mysql_error());

    $account = Account::login("jacob", "jacob");

    $params = (object) array("ownerId" => $account->id, "name" => "jacobs stuff", "startUrl" => "http://pedant.dk", "pageLimit" => 1500, "levelLimit" => 15);

    $collection = Collection::create($params);
    $collection->addDomain("pedant.dk");
    $collection->addDomain("searchzen.org");

    $this->assertEquals($collection->name, "jacobs stuff");
    $this->assertEquals($collection->domains[0]->name, "pedant.dk");
    $this->assertEquals($collection->domains[1]->name, "searchzen.org");
  }  

  public function testDestroy()
  {
    $account = Account::login("jacob", "jacob");
    Account::destroy($account->collections[0]->id);
  }

}
