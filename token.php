<?php
require_once "php/Framework.php";

$token= Account::getToken($userName, $password);

if (!(isset($token))){
  $token = Account::generateToken($userName, $password);
  }
print $token;
?>
