<?php
require_once("../classes/YASE.php");

if (!isset($argv[1])){
  $argv[1]="";
}
if(!isset($argv[2])){
  $argv[2]="";
}
if ($argv[1] == "" || $argv[2] == "") {
   print "YASE setup \r\n";
   print " usage:\r\n";
   print " setup.php [user] [account]\r\n";
   exit -1;
}

$user = $argv[1];
$domain= $argv[2];

$y = new YASE();

$y->addUser($user, "test");
$y->addAccount($user, $domain);
?>
