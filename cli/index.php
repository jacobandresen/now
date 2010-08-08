<?php
require_once('../php/YASE.php');

if(!isset($argv[1])){
   $argv[1]="";
}
$account = $argv[1];
if ($argv[1] == "") {
  print "YASE indexer\r\n";
  print "usage:\r\n";
  print "  index.php [userName] [password] [collectionId]\r\n";
  exit -1;
}

$account = Account::login($argv[1], $argv[2]);
$indexer = new Indexer($argv[3]);
$indexer->index();

?>
