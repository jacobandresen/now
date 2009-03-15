<?php
 include("../classes/Global.php");
 include("../classes/UserManagement.php");


 $u = new UserManagement();
 $u->addUser("korpen.se","test", "korpen.se");
 $u->addUser("pedant.dk","test", "pedant.dk");
 $u->addUser("efessexparktaarnby.dk", "test", "efessexparktaarnby.dk");
 $u->addUser("jci.dk", "test", "jci.dk");
 $u->addUser("johanbackstrom.se", "test", "johanbackstrom.se");
?>
