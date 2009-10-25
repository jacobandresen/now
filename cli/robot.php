<?php
require_once('../classes/HTTPRobot.php');

$account = $argv[1];

if ($argv[1] == "") {
  print "YASE job control\r\n";
  print "usage:\r\n";
  print "  job.php [account name] \r\n";
  exit -1;
}

set_time_limit(0);

$iAccountID=Account::getId($account);

try{
  $c = new Crawler($iAccountID);
  $c->start();
  $i = new Indexer($iAccountID);
  $i->start();
}catch($err){
  print "failed";
}

?>
