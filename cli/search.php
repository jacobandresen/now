<?php
require_once("../php/Framework.php");

if(!isset($argv[1])){
  $argv[1]="";
}
if(!isset($argv[2])){
  $argv[2]="";
}
$account = $argv[1];
if ($argv[1] == "" || $argv[2] == "") {
   print "YASE searcher \r\n";
   print "  usage:\r\n";
   print "  search.php [account] [query]\r\n";
   exit -1;
}


$s=new Searcher($argv[1]);
$s->search($argv[2], 0);

?>
