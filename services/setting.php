<?php
require_once('../classes/Global.php');
require_once('../classes/Setting.php');

$iAccountId     = $_REQUEST['account'];
$sAction	= $_REQUEST['action'];
$sTableName 	= $_REQUEST['table'];

$s  = new Setting($iAccountId, $sTableName);
//PUT
if ($sAction=="PUT") {
  $s->deleteAll(); 
  $jsonDATA = json_decode($_REQUEST['data']); 
  foreach($jsonDATA as $field){
    $s->put( $field->name, $field->value);
  }
}

//GET
if ($sAction=="GET"){
  print json_encode( $s->getAll());
}
?>

