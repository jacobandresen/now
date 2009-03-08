<?php
require_once ('../classes/Global.php');
require_once ('../classes/UserManagement.php');
require_once ('../classes/HTTPClient.php');
require_once ('../classes/Indexer.php');


$u = new UserManagement();
$iUserId = $u->getUserId("pedant.dk");

$i = new Indexer($iUserId);
$i->index();

?>
