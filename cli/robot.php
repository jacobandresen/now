<?php
require_once('../classes/HTTPRobot.php');


if(!isset($argv[1])){
   $argv[1]="";
}

$account = $argv[1];
if ($argv[1] == "") {
  print "YASE job control\r\n";
  print "usage:\r\n";
  print "  robot.php [account name] \r\n";
  exit -1;
}
set_time_limit(0);
$iAccountID=Account::getId($account);

try{
$c = new Crawler($iAccountID);
$c->start();
$i = new Indexer($iAccountID);
$i->start();
}catch(Exception $e){
   print_r($e->getMessage());
}

?>
