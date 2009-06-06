<?php

  require_once("classes/Global.php");
  require_once("classes/Controller.php");
  require_once("classes/Model.php");
  require_once("classes/Request.php");
  require_once("classes/Response.php");

  require_once("models/Setting.php");

  $request = new Request(array('restful' => true));
  
  if (isset($request->controller)) {
    require("controllers/".$request->controller.".php");
    $controller_name = ucfirst($request->controller);
    $controller = new $controller_name;
    echo $controller->dispatch($request); 
  }

 ?>

