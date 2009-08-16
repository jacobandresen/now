<?php
require_once("global.php");
require_once("../classes/YASE/Framework.php");
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

//execute pending jobs for all accounts for user
$iUserID=YASE_User::getId($sUser);
foreach( YASE_User::getAccounts($iUserID) as $acc) {
    JobDaemon::executePending($acc->iId);
}

?>
