<?php
require_once "YASE/Framework.php";

$userName = $_REQUEST['username'];
$password = $_REQUEST['password'];

$token= Account::getToken($userName, $password);

if (!(isset($token))){
  $token = Account::generateToken($userName, $password);
  }
print $token;
?>
