<?php
 include("../classes/Global.php");
 include("../classes/UserManagement.php");

 $u = new UserManagement();
 $u->addUser("pedant.dk","test", "http://pedant.dk");
 print $u->getUserId("pedant.dk");
 print $u->getDomainId("pedant.dk", "http://pedant.dk");
?>
