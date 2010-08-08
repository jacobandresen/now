<?php
require_once('../php/Framework.php');

if ($argv[1] == "") {
  print "YASE crawler\r\n";
  print "usage:\r\n";
  print "  crawl.php [userName] [password] [collectionId] \r\n";
  exit -1;
}

$account = Account::login($argv[1], $argv[2]);
print "logged in\r\n";
$crawler = new Crawler((object) array("id"=>$argv[3]));
$crawler->start();
?>
