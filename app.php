<?php

  require_once("classes/Global.php");
  require_once("rest/Controller.php");
  require_once("rest/Model.php");
  require_once("rest/Request.php");
  require_once("rest/Response.php");

  require_once("models/Setting.php");
  require_once("models/Account.php");

  $request = new Request(array('restful' => true));
  if (isset($request->controller)) {
    $controller_name = ucfirst($request->controller);
    require("controllers/".$controller_name.".php");
    $controller = new $controller_name;
    echo $controller->dispatch($request); 
  }
 ?>

