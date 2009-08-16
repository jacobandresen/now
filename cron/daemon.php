<?php
require_once("global.php");
require_once("../classes/YASE/Framework.php");
require_once("../classes/JobDaemon.php");


$account = $argv[1];
if ($argv[1] == "") {
    print "YASE job daemon\r\n";
    print "usage:\r\n";
    print " daemon.php [account name] \r\n";
    exit -1;
}  

$iAccountID=YASE_Account::getId($account);

set_time_limit(0);
JobDaemon::executePending(1);
?>
