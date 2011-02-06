<?php
require_once("YASE/Framework.php");

$token			= $_REQUEST['token'];
$controller = $_REQUEST['controller'];
$json   		= $_REQUEST['json'];
$action 		= $_REQUEST['action'];
$account 		= Account::tokenLogin($token);

if (isset($account)){
  $app = new JSONApplication();
  print $app->dispatch($controller, $action, $json);
}

?>
