<?php
require_once("php/Framework.php");

$controller  	= $_REQUEST['controller'];
$json   	= $_REQUEST['json'];
$action 	= $_REQUEST['action'];

try{
  $app = new JSONApplication();
  print $app->dispatch($controller, $action, $json);
}catch (Exception $e) {
  print "json call failed :".$e->getMesage()."\r\n";
}
?>
