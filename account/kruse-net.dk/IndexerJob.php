<?php
require_once ('../../classes/Global.php');
require_once ('../../classes/Yase.php');

$y=new Yase("kruse-net.dk");

$aFilterSkip=array();
array_push($aFilterSkip, "\/page\/");
array_push($aFilterSkip, "\/category\/");
array_push($aFilterSkip, "\/tag\/");
array_push($aFilterSkip, "wp-login");
array_push($aFilterSkip, "\.js");
array_push($aFilterSkip, "google\.com");
$y->oIndexer->aFilterSkip=$aFilterSkip;

$y->oIndexer->addBodyFilter("|div class=\"entry\">(.*?)class=\"rightmeta\"|is");

$y->index();

?>
