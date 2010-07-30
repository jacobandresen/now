<?php
require_once 'PHPUnit/Framework.php';
require_once '../php/Framework.php';

class AccountTest extends PHPUnit_Framework_TestCase
{
  public function testCreate()
  {
     mysql_query("DELETE FROM account where username='jacob'");
     Account::create("jacob", "jacob", "Jacob", "Andresen");
  }  

  public function testDelete()
  {
     mysql_query("DELETE FROM account where username='deleteme'");
     Account::create("deleteme", "deleteme", "Jacob", "Andresen");
     $account = Account::login("deleteme", "deleteme");
     Account::delete($account->id);
  }
}
