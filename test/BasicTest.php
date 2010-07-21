<?php
require_once 'PHPUnit/Framework.php';
require_once '../php/Framework.php';

class ManagerTest extends PHPUnit_Framework_TestCase
{
  public function testCreateAccount()
  {
     createAccount("jacob", "jacob", "Jacob", "Andresen");
  }  

  public function testDefaultAccountSettings()
  {
     $accountId = login("jacob", "jacob");
     $crawlerSetting = new Setting("crawler", $accountId);
     $this->assertEquals( $crawlerSetting->get("crawl_limit"), "1500");
     $this->assertEquals( $crawlerSetting->get("level_limit"), "15"); 
  }
 
  public function testDeleteAccount()
  {
     $accountId = login("jacob", "jacob");
     deleteAccount($accountId);
  }
}
