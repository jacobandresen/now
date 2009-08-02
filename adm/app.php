<?php
  require_once("../YASE/Framework.php");
  require_once("REST/Model.php");
  require_once("REST/Request.php");
  require_once("REST/Controller.php");
  require_once("REST/Response.php");
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

