<?php
require_once('../classes/Global.php');
require_once('../classes/Setting.php');

$iAccountId     = $_REQUEST['account'];
$sAction	= $_REQUEST['action'];
$sTableName 	= $_REQUEST['table'];
$iSettingId	= $_REQUEST['id'];


//if (!(isset($_SESSION['login'])) ) {
//  print "not logged in\r\n";
//  exit;
//}

if($iAccountId==''|| $sAction=='' || $sTableName=='') {
  print "misssing required parameter\r\n";
  exit;
}

$s  = new Setting($iAccountId, $sTableName);
if ($sAction=="GET"){
  $fields=$s->getAll();
  print ( json_encode( $fields )) ;
}

if ($sAction=="PUT"){
  $field = json_decode( $_REQUEST['data'] ); 
  $s->update($field->id, $field->name, $field->value, $field->type);  
}


if ($sAction=="POST") {
  $fields = json_decode($_REQUEST['data']); 
  foreach($fields as $field) { 
    $s->put( $field->name, $field->value);
  }
}

if ($sAction=="DELETE"){
  $s->delete($iSettingId);
}



?>

