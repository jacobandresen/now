<?php
require_once 'PHPUnit/Framework.php';
require_once '../php/Framework.php';

class AccountTest extends PHPUnit_Framework_TestCase
{
  public function testCreate()
  {
     Account::create("jacob", "jacob", "Jacob", "Andresen");
  }  

  public function testDefaultAccountSettings()
  {
     $loginId = Account::login("jacob", "jacob");
     $setting = new Setting("crawler", $loginId);
     $this->assertEquals( $setting->get("crawl_limit"), "1500");
     $this->assertEquals( $setting->get("level_limit"), "15"); 
  }
 
  public function testDelete()
  {
     $loginId = Account::login("jacob", "jacob");
     Account::delete($loginId);
  }
}
