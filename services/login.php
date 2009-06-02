<?php
require_once('../classes/Global.php');
require_once('../classes/UserManagement.php');

$sUser= 	$_REQUEST['user'];
$sPassword=     $_REQUEST['password'];

$u = new UserManagement();

if( $u->login( $sUser, $sPassword ) ) {
  print true;
} else {
  print false;
}
?>

 
