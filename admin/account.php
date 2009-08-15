<?
require_once("class_autoload.php"); 

Template::admintop();
 
$iAccountID=$_REQUEST['account_id'];
if(isset($iAccountID)){
    YASE_Account::changeAccount($iAccountID); 
} 
?>

<H1> Account information </H1>
account id:[<?=$_SESSION["account_domain"]?>]

<?php
print "[".$_SESSION["account_id"]."]"; 
Template::bottom();
?>

