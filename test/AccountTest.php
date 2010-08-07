<?php
require_once 'PHPUnit/Framework.php';
require_once '../php/Framework.php';

class AccountTest extends PHPUnit_Framework_TestCase
{
  public function testCreate()
  {
     mysql_query("DELETE FROM account where username='jacob'");
     $params = (object) array("userName"=>"jacob", "password"=>"jacob", "firstName"=>"Jacob", "lastName"=>"Andresen");
     Account::create($params);
  }  

  public function testDelete()
  {
     mysql_query("DELETE FROM account where username='deleteme'");
     Account::create( (object) array("userName"=>"deleteme", "password"=>"deleteme", "firstName"=>"Jacob", "lastName"=>"Andresen"));
     $account = Account::login("deleteme", "deleteme");
     Account::destroy($account->id);
  }
}
