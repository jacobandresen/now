<?php
 
 require_once('global.php'); 
 require_once('../classes/YASE/Framework.php');


 $id = YASE_User::getId("pedant.dk");
 $_SESSION['user_id']=$id;
 $aid= YASE_User::getFirstAccountId($id);
 print_r( YASE_Account::getDomain($aid)."\r\n" );
 
?>
