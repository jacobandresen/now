<?php
class JSONApplication
{
  public $response;

  public function __construct ( )
  {
    $resp =new Response();;
  }

  public function dispatch ($controller, $action, $json)
  {
     if (!isset($controller)) {
       throw new Exception("missing CONTROLLER parameter");
     }
     
     if (!isset($action)) {
       throw new Exception("missing ACTION parameter");
     }
     if (!isset($json)) {
       throw new Exception("missing JSON parameter");
     }

     $params = json_decode($json);
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
    return ( json_encode($resp) );
  }
}
?>
