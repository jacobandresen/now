<?php
require_once ('../../classes/Global.php');
require_once ('../../classes/Framework.php');

$y= new Yase("www.xn--schler-dya.net");

$aFilterSkip=array();
array_push($aFilterSkip, "\/page\/");
array_push($aFilterSkip, "\/category\/");
array_push($aFilterSkip, "\/tag\/");
array_push($aFilterSkip, "wp-login");
array_push($aFilterSkip, "\.js");
$y->oIndexer->aFilterSkip=$aFilterSkip;
$y->oIndexer->addBodyFilter("|div id=\"content\">(.*?)<div id=\"sidebar\"|is");
$y->index();

?>
