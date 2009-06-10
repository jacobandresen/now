<?php
require_once ('../../classes/Global.php');
require_once ('../../classes/Yase.php');

$y = new Yase("www3.ridsport.se");

$y->oIndexer->reset();

$aFilterSkip=array();
array_push($aFilterSkip, "javascript\:");
array_push($aFilterSkip, "\'");
array_push($aFilterSkip, "oas");
array_push($aFilterSkip, "ImageVault");
$y->oIndexer->aFilterSkip=$aFilterSkip;
//$y->oIndexer->addBodyFilter("|div class=\"entry-content\">(.*?)class=\"comments\"|is");

$y->index();
?>
