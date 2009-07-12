<?php
 
 require_once('../classes/Global.php'); 
 require_once('../classes/Yase.php');


 $id = User::getId("pedant.dk");
 foreach (User::getAccounts($id) as $a ) {
   print_r ($a);

 }

 
?>
