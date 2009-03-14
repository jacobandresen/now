<?php

include("../classes/Global.php");
include("../classes/Store.php");
include("../classes/UserManagement.php");

$u = new UserManagement();
$iUserId = $u->getUserId("pedant.dk");
$iDomainId=$u->getDomainId("pedant.dk", "pedant.dk");

print "DOMAIN:".$iDomainId."\r\n";

$s = new Store( $iDomainId, "crawlskip");
$s->deleteAll();
$s->put("feed","feed");
$s->put("xml file","\.xml");
$s->put("css file","\.css"); 
$s->put("jpeg ", "\.jpg|\.jpeg");
$fs = $s->getAll(); 
for($i=0;$i<sizeof($fs);$i++){
 print $fs[$i]->name."\t".$fs[$i]->value."\r\n";	
}

?>
