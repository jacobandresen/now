<?php
require_once("php/Framework.php");

$controller  	= $_REQUEST['controller'];
$json   	= $_REQUEST['json'];
$action 	= $_REQUEST['action'];

$app = new JSONApplication();
print $app->dispatch($controller, $action, $json);
?>
