<?php
require_once ('../../classes/Global.php');
require_once ('../../classes/Yase.php');

$y = new Yase("jci.dk");
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

$y->oCrawler->aFilterSkip = $aFilterSkip;
$y->crawl();
?>
