<?php
 include("../classes/Global.php");
 include("../classes/UserManagement.php");

 $u = new UserManagement();

 $u->clearAll();

 $u->addUser("korpen.se","test", "www.korpen.se");
 $u->addUser("pedant.dk","test", "pedant.dk");
 $u->addUser("efessexparktaarnby.dk", "test", "efessexparktaarnby.dk");
 $u->addUser("jci.dk", "test", "jci.dk");
 $u->addUser("johanbackstrom.se", "test", "johanbackstrom.se");
 $u->addUser("gianelli.dk", "test", "gianelli.dk");
 $u->addUser("jaksm.dk", "test", "jaksm.dk"); 
 $u->addUser("kruse-net.dk", "test", "kruse-net.dk");
 $u->addUser("www.xn--schler-dya.net", "test", "www.xn--schler-dya.net");
 $u->addUser("www.sjv.se", "test", "www.sjv.se");
 $u->addUser("support.dataaccess.com", "test", "support.dataaccess.com");
?>
