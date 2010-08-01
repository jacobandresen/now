<?php

require_once("php/Framework.php");

$controller  	= $_REQUEST['controller'];
$json   	= $_REQUEST['json'];
$action 	= $_REQUEST['action'];
$success	= false;

$controllers    = array();
$controllers[0] = "Collection";
$controllers[1] = "Domain";

class Response 
{
  public $data;
  public $success;
}

$resp =new Response();;

if (!isset($controller)) {
  return;
}

if (isset($json)) {
  $params = json_decode($json);
}
$controllerObject = new $controller();
switch ($action) {
      case "create":
	 $collection = $controllerObject::create($params);	
	 $resp->id = $collection->id;
         break;
      case "retrieve":
         $resp->data = $controllerObject::retrieve($params->id);
         $resp->success = true;
        break;
      case "update":
         $controllerObject::update($params);
         $resp->success = true;
	 break; 
      case "destroy":
         $controllerObject::destroy($params->id);
         $resp->success = true;	         
         break;
  } 
print json_encode($resp);
?>
