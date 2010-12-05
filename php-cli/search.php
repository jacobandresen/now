<?php
require_once("../php/YASE/Framework.php");

if (sizeof($argv) < 4 || $argv[0] == "" || $argv[1] == "") {
   print "YASE searcher \r\n";
   print "  usage:\r\n";
   print "  search.php [userName] [password] [query]\r\n";
   exit -1;
}

$account = Account::login($argv[1], $argv[2]);
$s=new Searcher($account->collections[0]);
print_r ($s->search($argv[3], 0));
?>
