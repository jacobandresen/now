<?php
require_once ('../../classes/Global.php');
require_once ('../../classes/Yase.php');


$y = new Yase("korpen.se");

$aFilterSkip = array();
array_push( $aFilterSkip, "print");
array_push( $aFilterSkip, "\.pdf");
array_push( $aFilterSkip, "\.ppt");
array_push( $aFilterSkip, "\.jpeg");
array_push( $aFilterSkip, "\.jpg");
array_push( $aFilterSkip, "\.gif");
array_push( $aFilterSkip, "\.zip");
array_push( $aFilterSkip, "\.doc");

$y->oCrawler->aFilterSkip = $aFilterSkip;

$y->crawl();
?>
