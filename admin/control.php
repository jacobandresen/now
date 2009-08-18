<?
require_once("class_autoload.php"); 
require_once("../classes/JobDaemon.php");

$sAction        =$_REQUEST['action'];
$iJobID         =$_REQUEST['job_id'];
$iAccountID     =$_SESSION['account_id'];
$sType          =$_REQUEST['type'];

$jd=new JobDaemon();
Template::admintop();
?>
<H1>Control crawler and indexer for <?=$_SESSION['account_domain'];?></H1>

<?php

if ( (isset($sAction)) && ($sAction=="cancel") && (isset($iJobID)) ){
    print "<p>cancelled job ".$iJobID."</p>";
    $jd->cancel($iJobID);
}

if (isset($iAccountID) && isset($sType) ){
  $jd->schedule($iAccountID, $sType, date('Y-m-d H:i:s') );
  print "<p>scheduled job for ".$sType."</p>";
 }
print "Pending jobs:";
print "<ul>";
foreach( $jd->listPending($iAccountID) as $jobVO ) {
    print "<li>[".$jobVO->iID."]".$jobVO->sType." -> ".$jobVO->dJobStart;
    print " <a href=\"control.php?action=cancel&job_id=".$jobVO->iID."\">cancel</a></li> ";
}
print "</ul>";
?>

<br>
start new jobs:
<br>

<ul>
    <li><a href="control.php?type=crawler">start crawler</a></li>
    <li><a href="control.php?type=indexer">start indexer</a></li>
</ul>

<?php
Template::bottom();
?>
