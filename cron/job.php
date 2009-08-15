<?
require_once('global.php'); 
require_once('../classes/YASE/Framework.php');

$account = $argv[1];
if ($argv[1] == "") {
    print "YASE job control\r\n"; 
    print "usage:\r\n";
    print "  job.php [account name] \r\n";
    exit -1;
}

//this can take a long time
set_time_limit(0);

$iAccountID=YASE_Account::getId($account);

$c = new YASE_Crawler($iAccountID);
$c->start();

$i = new YASE_Indexer($iAccountID);
$i->start();
?>
