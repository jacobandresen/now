<?php
require_once 'PHPUnit/Framework.php';
require_once '../php/Framework.php';

class AccountTest extends PHPUnit_Framework_TestCase
{
  public function testAddCollection()
  {
    $account = login("jacob", "jacob");
    $collection = new Collection($account->id, "jacobs stuff");
    $collection->addDomain("pedant.dk");
    $account->addCollection($collection);
  }  

  public function testDeleteCollection()
  {
  }
}
