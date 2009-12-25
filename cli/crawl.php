<?php
require_once('../classes/YASE.php');

if(!isset($argv[1])){
   $argv[1]="";
}

$account = $argv[1];
if ($argv[1] == "") {
  print "YASE crawler\r\n";
  print "usage:\r\n";
  print "  crawl.php [account name] \r\n";
  exit -1;
}

$s = new YASE();
$s->crawl($argv[1]);
?>
