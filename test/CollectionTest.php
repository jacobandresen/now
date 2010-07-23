<?php
require_once 'PHPUnit/Framework.php';
require_once '../php/Framework.php';

class CollectionTest extends PHPUnit_Framework_TestCase
{
  public function testAdd()
  { 
    $account = Account::login("jacob", "jacob");
    mysql_query("DELETE FROM collection where owner_id=".$account->id) or die(mysql_error());

    $collection = Collection::create($account->id, "jacobs stuff");
    $collection->addDomain("pedant.dk");

    $this->assertEquals($collection->name, "jacobs stuff");
    $this->assertEquals($collection->domains[0], "pedant.dk");
  }  

}
