<?php
require_once ('../../classes/Global.php');
require_once ('../../classes/Yase.php');

$y = new Yase("www.xn--schler-dya.net");

$aFilterSkip = array();

//array_push( $aFilterSkip, "print");
array_push( $aFilterSkip, "\.pdf");
array_push( $aFilterSkip, "\.PDF");
array_push( $aFilterSkip, "\.ppt");
array_push( $aFilterSkip, "\.PPT");
array_push( $aFilterSkip, "\.jpeg");
array_push( $aFilterSkip, "\.JPEG");
array_push( $aFilterSkip, "\.jpg");
array_push( $aFilterSkip, "\.JPG");
array_push( $aFilterSkip, "\.gif");
array_push( $aFilterSkip, "\.GIF");
array_push( $aFilterSkip, "\.zip");
array_push( $aFilterSkip, "feed");
array_push( $aFilterSkip, "\.xml");
array_push( $aFilterSkip, "\.css");
array_push( $aFilterSkip, "\.CSS");
array_push( $aFilterSkip, "xmlrpc");
array_push( $aFilterSkip, "\#respond");
array_push( $aFilterSkip, "\#comment");
array_push( $aFilterSkip, "\.war");
array_push( $aFilterSkip , "audioselect");
array_push( $aFilterSkip, "antiselect");
array_push( $aFilterSkip, "jigsaw");

$y->oCrawler->aFilterSkip = $aFilterSkip;

$y->crawl();
?>
