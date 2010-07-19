<?php
require_once 'PHPUnit/Framework.php';
require_once '../php/Framework.php';

class AccountTest extends PHPUnit_Framework_TestCase
{
  public function testCanCreateNewAccount()
  {
     Account::create("jacob", "jacob", "Jacob", "Andresen");
     $loginId = Account::login("jacob", "jacob");
  }  

  public function testCanDeleteExistingAccount()
  {
     $loginId = Account::login("jacob", "jacob");
     Account::delete($loginId);
  }

  public function testAreTheDefaultSettingsCorrect()
  {
     Account::create("jacob", "jacob", "Jacob", "Andresen");
     //assert crawl_limit exists
     //assert level_limit exists     
  }
}
