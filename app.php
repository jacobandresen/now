<?php

  require_once("classes/Global.php");
  require_once("classes/Controller.php");
  require_once("classes/Model.php");
  require_once("classes/Request.php");
  require_once("classes/Response.php");

  require_once("models/Setting.php");
  require_once("models/Account.php");

  $request = new Request(array('restful' => true));

  if (isset($request->controller)) {
    //TODO: check if the file exists 
    $controller_name = ucfirst($request->controller);
    require("controllers/".$controller_name.".php");
    $controller = new $controller_name;
    echo $controller->dispatch($request); 
  }

 ?>

