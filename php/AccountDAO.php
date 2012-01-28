<?php 
require_once("DAO.php");

class AccountDAO extends DAO
{
};

$account = new AccountDAO();
$a = $account->retrieve("1");

print_r($a);
?>
