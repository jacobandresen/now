<?php
require_once("../php/Framework.php");

if (!isset($argv[1])){
  $argv[1]="";
}
if(!isset($argv[2])){
  $argv[2]="";
}
if ($argv[1] == "" || $argv[2] == "") {
   print "YASE setup \r\n";
   print " usage:\r\n";
   print " setup.php [username] [password]\r\n";
   exit -1;
}

$userName = $argv[1];
$password= $argv[2];

//$y = new YASE();
//$y->addAccount($userName, $password);
?>
