<?php
require_once("global.php");
require_once("../classes/Framework.php");
require_once("../classes/JobDaemon.php");

$sUser = $argv[1];
if ($argv[1] == "") {
    print "YASE job daemon\r\n";
    print "usage:\r\n";
    print " daemon.php [user name] \r\n";
    exit -1;
}  

//this can take a long time
set_time_limit(0);

$jd=new JobDaemon();

$iUserID=User::getId($sUser);
foreach( User::getAccounts($iUserID) as $acc) {
      $jd->executePending($acc->iId);
}
?>
