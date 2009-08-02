<?php
  require_once("classes/Global.php"); 
  require_once("classes/Yase.php");
  require_once("classes/REST/Model.php");
  require_once("classes/REST/Request.php");
  require_once("classes/REST/Controller.php");
  require_once("classes/REST/Response.php");
  require_once("app/models/Crawlerfilter.php");
  require_once("app/models/Indexerfilter.php");


  $request = new REST_Request(array('restful' => true));
  if (isset($request->controller)) {
    $controller_name = ucfirst($request->controller);
    require("app/controllers/".$controller_name.".php");
    $controller = new $controller_name;
    echo $controller->dispatch($request); 
  }
?>

