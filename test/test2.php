<?php
 
 require_once('../classes/Global.php'); 
 require_once('../classes/Yase.php');


 $id = User::getId("pedant.dk");
// foreach (User::getAccounts($id) as $a ) {
//   print_r ($a);
//
// }
 $_SESSION['user_id']=$id;
 $aid= User::getFirstAccountId($id);
 print_r( Account::getDomain($aid)."\r\n" );
 
?>
