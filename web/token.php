<?php
require_once "configuration.php";
require_once "YASE/Framework.php";

header('Content-type: application/json');

$userName = $_REQUEST['username'];
$password = $_REQUEST['password'];

$token= Account::getToken($userName, $password);

if (!(isset($token))){
    $token = Account::generateToken($userName, $password);
}
print Account::tokenLogin($token);
?>
