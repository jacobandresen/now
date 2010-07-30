<?php

require_once("php/Framework.php");

$model  = $_REQUEST['model'];
$data   = $_REQUEST['data'];
$action = $_REQUEST['action'];
$query  = $_REQUEST['query'];
$start  = $_REQUEST['start'];
$limit  = $_REQUEST['limit'];

$success=false;

$resp={};
$resp->data={};

switch ($model) {
  case "collection":
    switch ($action) {
      case "create":
        break;
      case "retrieve":
         //$resp->data->name = 
         //$resp->data->pageLimit=	
	 //$resp->data->levelLimit=
        break;
      case "delete":
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

print "{success:".$success."}";
?>
