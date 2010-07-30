<?php

require_once("php/Framework.php");

//TODO: avoid sql injection
$model  = $_REQUEST['model'];
$data   = $_REQUEST['data'];
$action = $_REQUEST['action'];
$query  = $_REQUEST['query'];
$start  = $_REQUEST['start'];
$limit  = $_REQUEST['limit'];

$success=false;

$resp={};
$resp->data={};
$resp->success=false;

switch ($model) {
  case "collection":
    switch ($action) {
      case "create":
         Collection::create($data);	     
         break;
      case "retrieve":
         $resp->data = Collection::retrieve($data->id);
         $resp->success = true;
        break;
      case "update":
         Collection::update($data);
         $resp->success = true;
	 break; 
      case "destroy":
         Collection::destroy($data->id);
         $resp->success = true;	         
         break;
    } 
    break;
  case "collectiondomain":
    switch ($action) {
      case "create":
        break;
      case "retrieve":
	break;
      case "update": 
	break;
      case "delete":
	break;
    } 
    break;
  case "crawlerfilter":
    switch ($action) {
      case "create":
        break;
      case "retrieve":
	break;
      case "update": 
	break;
      case "delete":
	break;
    } 
    break;
  case "indexerfilter":
     switch ($action) {
      case "create":
        break;
      case "retrieve":
	break;
      case "update": 
	break;
      case "delete":
	break;
    } 
    break;
  case "query":
    switch ($action) {
      case "create":
        break;
      case "retrieve":
	break;
      case "update": 
	break;
      case "delete":
	break;
    } 
    break;
}
//TODO: print "Content-Type: application/json \r\n";
print json_encode($resp);
?>
