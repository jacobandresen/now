<?php
 
 require_once('YASE/Framework.php');


 $id = User::getId("pedant.dk");
 $_SESSION['user_id']=$id;
 $aid= User::getFirstAccountId($id);
 print_r( Account::getDomain($aid)."\r\n" );
 
?>
