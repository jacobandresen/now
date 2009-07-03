<?php
  require_once("classes/Yase.php");
 
  //model
  require_once("app/models/Setting.php");
  require_once("app/models/Account.php");

  //controller
  $request = new REST_Request(array('restful' => true));
  if (isset($request->controller)) {
    $controller_name = ucfirst($request->controller);
    require("app/controllers/".$controller_name.".php");
    $controller = new $controller_name;
    echo $controller->dispatch($request); 
  }
?>

