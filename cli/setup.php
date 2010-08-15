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
   print " setup.php [username] [password] [domain]\r\n";
   exit -1;
}

$userName = $argv[1];
$password = $argv[2];
$domain   = $argv[3]; 

$account = Account::create( (object) array("userName"=>$userName, "password"=>$password, "firstName"=>"", "lastName"=>""));

$collection = Collection::create( (object) array("ownerId"=>$account->id, "name" => "jacobs stuff", "startUrl" => "http://pedant.dk", "pageLimit" => 1500, "levelLimit" => 15));
$collection->addDomain("pedant.dk"); 
?>
