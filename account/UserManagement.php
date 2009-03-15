<?php
 include("../classes/Global.php");
 include("../classes/UserManagement.php");

 //TODO: take command line params

 $u = new UserManagement();
 $u->addUser("korpen.se","test", "korpen.se");
 $u->addUser("pedant.dk","test", "pedant.dk");

?>
