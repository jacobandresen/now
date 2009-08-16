<?
require_once("class_autoload.php"); 

Template::admintop();
 
$iAccountID=$_REQUEST['account_id'];
if(isset($iAccountID)){
    YASE_Account::changeAccount($iAccountID); 
} 
?>

<H1> Account information </H1>
<p>
account domain:<?=$_SESSION["account_domain"]?>
</p>

<p>
accound id:<?=$_SESSION["account_id"]?>
</p>

<ul>
 <li> TODO: export settings </li>
 <li> TODO: import settings </li>
</ul>

<?php
Template::bottom();
?>

