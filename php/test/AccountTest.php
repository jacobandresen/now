<?php
require_once 'configuration.php';
require_once 'PHPUnit/Framework.php';
require_once '../main/Framework.php';

class AccountTest extends PHPUnit_Framework_TestCase
{
    public function testCreate()
    {
        pg_query("DELETE FROM account where user_name='searchzen.org'") ;
        $params = (object)array("userName" => "searchzen.org",
            "password" => "test",
            "firstName" => "Jacob",
            "lastName" => "Andresen");
        Account::create($params);
    }

    public function testDelete()
    {
        pg_query("DELETE FROM account where user_name='deleteme'");
        $account = Account::create((object)array("userName" => "deleteme",
            "password" => "deleteme",
            "firstName" => "Jacob",
            "lastName" => "Andresen"));
        Account::destroy($account->id);
    }

    public function testTokenLogin()
    {
        $token = Account::generateToken("searchzen.org", "test");
        $account = Account::tokenLogin($token);
        $this->assertEquals($account->userName, "searchzen.org");
    }
}
