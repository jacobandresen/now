<?
require_once("class_autoload.php"); 
require_once("../classes/JobDaemon.php");

$iAccountID=$_SESSION['account_id'];
$sType=$_REQUEST['type'];

Template::admintop();
?>
<H1>Control crawler and indexer for <?=$_SESSION['account_domain'];?></H1>


<?php

//TODO: list pending jobs

if (isset($iAccountID) && isset($sType) ){
  JobDaemon::schedule($iAccountID, $sType, date('Y-m-d H:i:s') );
  print "scheduled job for ".$sType;
 }

?>

<br>
Schedule jobs:
<br>

<ul>
    <li><a href="control.php?type=crawler">start crawler</a></li>
    <li><a href="control.php?type=indexer">start indexer</a></li>
</ul>


<?php
Template::bottom();
?>


