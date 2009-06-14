<?
 require_once('../classes/Yase.php');
 $account = $argv[1];
 if ($argv[1] == "") {
   print "usage:\r\n";
   print "  job.php [account name] \r\n";
   exit -1;
 }
 $y = new Yase($account);
 $y->crawl();
 $y->index();
?>
