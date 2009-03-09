<?php
 include("../classes/Global.php");
 include("../classes/UserManagement.php");

 $u = new UserManagement();
 $u->addUser("pedant.dk","test", "pedant.dk");

 $aDomain = $u->getDomains($u->getUserId("pedant.dk"));
 for ($i=0;$i<sizeof($aDomain);$i++){
  print $aDomain[$i];

 } 

?>
