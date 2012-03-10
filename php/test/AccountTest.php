<?php
require_once 'configuration.php';
require_once 'PHPUnit/Framework.php';
require_once '../main/Framework.php';

class AccountTest extends PHPUnit_Framework_TestCase
{
    public function testCreate()
    {
        mysql_query("DELETE FROM account where username='pedant.dk'") or die ("delete failed:" . mysql_error());
        $params = (object)array("userName" => "pedant.dk", "password" => "test", "firstName" => "Jacob", "lastName" => "Andresen");
        Account::create($params);
    }

    public function testDelete()
    {
        mysql_query("DELETE FROM account where username='deleteme'");
        $account = Account::create((object)array("userName" => "deleteme", "password" => "deleteme", "firstName" => "Jacob", "lastName" => "Andresen"));
        Account::destroy($account->id);
    }

    public function testTokenLogin()
    { 
        $token = Account::generateToken("pedant.dk", "test");
        $loggedIn = Account::tokenLogin($token);
        $this->assertEquals($loggedIn, true);
    }
}
