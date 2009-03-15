<?php
require_once ('../../classes/Global.php');
require_once ('../../classes/UserManagement.php');
require_once ('../../classes/HTTPClient.php');
require_once ('../../classes/Crawler.php');

$u = new UserManagement();
$iUserId = $u->getUserId("johanbackstrom.se");

$c = new Crawler($iUserId);
$aFilterSkip = array();


array_push( $aFilterSkip, "print");
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
array_push( $aFilterSkip, "\.wmv");
array_push( $aFilterSkip, "\.js");
array_push( $aFilterSkip, "\&\#");


$c->aFilterSkip = $aFilterSkip;
$c->crawler("http://johanbackstrom.se", 0, "http://johanbackstrom.se");

?>
