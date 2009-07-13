<?
 require_once('../classes/Global.php');
 require_once('../classes/Yase.php');

 $account = $argv[1];
 if ($argv[1] == "") {
   print "usage:\r\n";
   print "  job.php [account name] \r\n";
   exit -1;
 }

 $c = new Crawler(Account::getId($argv[1]));
 $c->start();

 $i = new Indexer(Account::getId($argv[1]));
 $i->start();
?>
