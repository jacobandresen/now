<?php
require_once("global.php"); 
require_once("class_autoload.php");

require_once("app/lib/Model.php");
require_once("app/lib/Request.php");
require_once("app/lib/Controller.php");
require_once("app/lib/Response.php");
require_once("app/models/Crawlerfilter.php");
require_once("app/models/Indexerfilter.php");

$request = new Request(array('restful' => true));
if (isset($request->controller)) {
    $controller_name = ucfirst($request->controller);
    require("app/controllers/".$controller_name.".php");
    $controller = new $controller_name;
    echo $controller->dispatch($request); 
}
?>

