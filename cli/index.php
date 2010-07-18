<?php
require_once('../php/YASE.php');

if(!isset($argv[1])){
   $argv[1]="";
}
$account = $argv[1];
if ($argv[1] == "") {
  print "YASE indexer\r\n";
  print "usage:\r\n";
  print "  index.php [account name] \r\n";
  exit -1;
}

$s = new YASE();
$s->index($argv[1]);

?>
