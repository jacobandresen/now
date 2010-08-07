<?php
require_once("php/Framework.php");
require_once("php/Response.php");
require_once("php/JSONApplication.php");

$controller  	= $_REQUEST['controller'];
$json   	= $_REQUEST['json'];
$action 	= $_REQUEST['action'];

$app = new JSONApplication();
print $app->dispatch($controller, $action, $json);
?>
