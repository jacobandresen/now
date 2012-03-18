<?php
require_once('now/Framework.php');

$user  = $_GET["user"];
$pass  = $_GET['pass'];


try{
    $token = Account::generateToken($user,$pass);
    print "{'success':true, token:'$token'}";
} catch (Exception $e) {
    print "{'succes':false, token:''}";
}

?>
