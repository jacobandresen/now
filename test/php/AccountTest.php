<?php
require_once 'PHPUnit/Autoload.php';
require_once 'YASE/Framework.php';

class AccountTest extends PHPUnit_Framework_TestCase
{
  public function testCreate()
  {
     mysql_query("DELETE FROM account where username='pedant.dk'");
     $params = (object) array("userName"=>"pedant.dk", "password"=>"test", "firstName"=>"Jacob", "lastName"=>"Andresen");
     Account::create($params);
  }  

  public function testDelete()
  {
     mysql_query("DELETE FROM account where username='deleteme'");
     $account = Account::create( (object) array("userName"=>"deleteme", "password"=>"deleteme", "firstName"=>"Jacob", "lastName"=>"Andresen"));
     Account::destroy($account->id);
  }
  
  public function testTokenLogin()
  {
     $token = Account::generateToken("pedant.dk", "test");
     $account = Account::tokenLogin($token);
     $this->assertEquals($account->userName, "pedant.dk");
  }

  public function testWebLogin()
  {
     Account::generateToken("pedant.dk", "test");
     $client = new HTTPClient();
     $url = YASE_WEB."/token.php?username=pedant.dk&password=test";
     $token =  $client->get($url)  ;    
  }
}
