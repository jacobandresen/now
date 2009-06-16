<?php
  require_once("classes/Yase.php");
 
  //model
  require_once("app/models/Setting.php");
  require_once("app/models/Account.php");

  //HACK: we only want to  display settings for a single account
  //  set account id for now. account should be chosen using combobox later
  $_SESSION['account_id']=12;

  //controller
  $request = new REST_Request(array('restful' => true));
  if (isset($request->controller)) {
    $controller_name = ucfirst($request->controller);
    require("app/controllers/".$controller_name.".php");
    $controller = new $controller_name;
    echo $controller->dispatch($request); 
  }
?>

