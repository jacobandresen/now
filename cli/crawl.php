<?php
require_once('../php/Framework.php');

if (sizeof($argv) < 3 || $argv[0] == "" || $argv[1] == "") {
  print "YASE crawler\r\n";
  print "usage:\r\n";
  print "  crawl.php [userName] [password] \r\n";
  exit -1;
}

$account = Account::login($argv[1], $argv[2]);
$crawler = new Crawler($account->collections[0]);
$crawler->start();

?>
