<?php
require_once ('../../classes/Global.php');
require_once ('../../classes/UserManagement.php');
require_once ('../../classes/HTTPClient.php');
require_once ('../../classes/Indexer.php');

$u = new UserManagement();
$iUserId = $u->getUserId("gianelli.dk");
$i = new Indexer($iUserId);
$i->reset();

$aFilterSkip=array();
$i->aFilterSkip=$aFilterSkip;
$i->index();

?>
