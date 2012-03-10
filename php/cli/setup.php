<?php
require_once("configuration.php");
require_once("../main/Framework.php");

if (!isset($argv[1])) {
    $argv[1] = "";
}
if (!isset($argv[2])) {
    $argv[2] = "";
}
if ($argv[1] == "" || $argv[2] == "") {
    print "NOW setup \r\n";
    print " usage:\r\n";
    print " setup.php [username] [password] [domain]\r\n";
    exit - 1;
}

$userName = $argv[1];
$password = $argv[2];
$domain = $argv[3];

$account = Account::create((object)array("userName" => $userName, "password" => $password, "firstName" => "", "lastName" => ""));
$collection = Collection::create((object)array("accountId" => $account->id, "name" => $domain, "startUrl" => $domain, "pageLimit" => 1500, "levelLimit" => 15));
$collection->addDomain($userName);
?>
