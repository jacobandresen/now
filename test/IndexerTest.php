<?php
require_once ('../classes/Global.php');
require_once ('../classes/UserManagement.php');
require_once ('../classes/HttpClient.php');
require_once ('../classes/search/Indexer.php');


$u = new UserManagement();
$iUserId = $u->getUserId("pedant.dk");

$i = new Indexer($iUserId);
$i->index();

?>
