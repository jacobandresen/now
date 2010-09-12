<?php
require_once('../php/Framework.php');

if (sizeof($argv) < 3 || $argv[0] == "" || $argv[1] == "") {
  print "YASE crawler filter control\r\n";
  print "usage:\r\n";
  print "  crawlerfilter.php [userName] [password] [filter]\r\n";
  exit -1;
}


$account = Account::login($argv[1], $argv[2]);
$domain = $account->collections[0]->domains[0];

$SQL = 'insert into filter(parent_id,name,regex) values("'.$domain->id.'","crawlerfilter","'.$argv[3].'")';

mysql_query($SQL) or die (mysql_error());
?>
