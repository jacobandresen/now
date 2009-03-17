<?php
require_once ('../../classes/Global.php');
require_once ('../../classes/UserManagement.php');
require_once ('../../classes/HTTPClient.php');
require_once ('../../classes/Crawler.php');

$u = new UserManagement();
$iUserId = $u->getUserId("gianelli.dk");

$c = new Crawler($iUserId);
$c->reset();
$aFilterSkip = array();

//array_push( $aFilterSkip, "print");
array_push( $aFilterSkip, "\.pdf");
array_push( $aFilterSkip, "\.ppt");
array_push( $aFilterSkip, "\.jpeg");
array_push( $aFilterSkip, "\.jpg");
array_push( $aFilterSkip, "\.gif");
array_push( $aFilterSkip, "\.zip");
array_push( $aFilterSkip, "feed");
array_push( $aFilterSkip, "\.css");
array_push( $aFilterSkip, "\.xml");
array_push( $aFilterSkip, "xmlrpc");
array_push( $aFilterSkip, "\#respond");
array_push( $aFilterSkip, "\#comment");
array_push( $aFilterSkip, "\.war");

$c->aFilterSkip = $aFilterSkip;
$c->crawler("http://www.gianelli.dk", 0, "http://www.gianelli.dk");

?>
