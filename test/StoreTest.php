<?php

include("../classes/Global.php");
include("../classes/Store.php");
include("../classes/userManagement.php");

$u = new UserManagement();


$iUserId = $u->getUserId("pedant.dk");
$iDomainId=$u->getDomainId("pedant.dk", "http://pedant.dk");

print "USER:".$iUserId."\r\n";
print "DOMAIN:".$iDomainId."\r\n";

$s = new Store( $iDomainId, "crawlskip");
$s->Put("test","test");
$s->put("test1","test2");

$fs = $s->getAll(); 

for($i=0;$i<sizeof($fs);$i++){
 print $fs[$i]->name;	
	
}
?>
