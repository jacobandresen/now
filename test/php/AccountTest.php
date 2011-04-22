<?php
require_once 'configuration.php';
require_once 'PHPUnit/Framework.php';
require_once 'YASE/Framework.php';

class AccountTest extends PHPUnit_Framework_TestCase
{
    public function testCreate()
    {
        $params = (object)array("username" => "pedant.dk", "password" => "test", "firstName" => "Jacob", "lastName" => "Andresen");
        $a = new Account();
        $a->create($params);
    }

    public function testDelete()
    {
        $a = new Account();
        $a->create((object)array("username" => "deleteme", "password" => "deleteme", "firstName" => "Jacob", "lastName" => "Andresen"));

        print_r($a);
        $a->destroy($a->id);
    }

}
