<?
 require_once("classes/Yase.php"); 

 $iAccountID=$_REQUEST['account_id'];
 require_once("app/template.php"); 
 require_once("app/login.php"); //this page requires a login

 Template::head();
 Template::leftbar();
 
 if(isset($iAccountID)){
   Account::changeAccount($iAccountID); 
   $_SESSION["account_id"]=$iAccountID;
   $_SESSION["account_domain"]=Account::getDomain( $_SESSION['account_id']);
 } 
?>

<br>
<H1> Account information </H1>
<br><br>
account id:[<?php print $_SESSION["account_domain"];?>]

<?php
 print "[".$_SESSION["account_id"]."]"; 
 Template::bottom();
?>

