<?
 require_once("../YASE/Framework.php"); 
 require_once("../template.php"); 

 $iAccountID=$_REQUEST['account_id'];
 

 Template::login(); 
 Template::head();
 Template::leftbar();
 
 if(isset($iAccountID)){
   Account::changeAccount($iAccountID); 
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

