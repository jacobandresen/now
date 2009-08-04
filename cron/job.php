<?
 require_once('../classes/YASE/Framework.php');

 $account = $argv[1];
 if ($argv[1] == "") {
   print "usage:\r\n";
   print "  job.php [account name] \r\n";
   exit -1;
 }
 $iAccountID=YASE_Account::getId($argv[1]);

 $c = new YASE_Crawler($iAccountID);
 $c->start();

 $i = new YASE_Indexer($iAccountID);
 $i->start();
?>
