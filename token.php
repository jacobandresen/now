<?php
require_once "php/Framework.php";

$userName = "pedant.dk"; //$_REQUEST['username'];
$password = "test"; //$_REQUEST['password'];

$token= Account::getToken($userName, $password);

if (!(isset($token))){
  $token = Account::generateToken($userName, $password);
  }
print $token;
?>
