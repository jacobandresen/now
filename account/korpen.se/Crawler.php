<?php
require_once ('../../classes/Global.php');
require_once ('../../classes/UserManagement.php');
require_once ('../../classes/HTTPClient.php');
require_once ('../../classes/Crawler.php');

$u = new UserManagement();
$iUserId = $u->getUserId("korpen.se");

$c = new Crawler($iUserId);
$aFilterSkip = array();

array_push( $aFilterSkip, "print");
array_push( $aFilterSkip, "\.pdf");
array_push( $aFilterSkip, "\.ppt");
array_push( $aFilterSkip, "\.jpeg");
array_push( $aFilterSkip, "\.jpg");
array_push( $aFilterSkip, "\.gif");
array_push( $aFilterSkip, "\.zip");
$c->aFilterSkip = $aFilterSkip;
$c->crawler("http://korpen.se", 0, "http://korpen.se");

?>
