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

  public function testDefaultSettings()
  {
     $account = Account::login("jacob", "jacob");
     $this->assertEquals( $account->crawlerSetting->get("crawl_limit"), "1500");
     $this->assertEquals( $account->crawlerSetting->get("level_limit"), "15"); 
  }
 
  public function testDelete()
  {
     mysql_query("DELETE FROM account where username='deleteme'");
     Account::create("deleteme", "deleteme", "Jacob", "Andresen");
     $account = Account::login("deleteme", "deleteme");
     Account::delete($account->id);
  }
}
