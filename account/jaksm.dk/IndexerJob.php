<?php
require_once ('../../classes/Global.php');
require_once ('../../classes/UserManagement.php');
require_once ('../../classes/HTTPClient.php');
require_once ('../../classes/Indexer.php');

$u = new UserManagement();
$iUserId = $u->getUserId("jaksm.dk");
$i = new Indexer($iUserId);
$i->reset();

$aFilterSkip=array();
//array_push($aFilterSkip, "\/page\/");
//array_push($aFilterSkip, "\/category\/");
//array_push($aFilterSkip, "\/tag\/");
array_push($aFilterSkip, "wp-login");
$i->aFilterSkip=$aFilterSkip;

//$i->addBodyFilter("|div class=\"entry-content\">(.*?)class=\"entry-meta\"|is");

$i->index();

?>
