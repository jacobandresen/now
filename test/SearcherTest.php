<?php
require_once ('../classes/Global.php');
require_once ('../classes/UserManagement.php');
require_once ('../classes/HTTPClient.php');
require_once ('../classes/Searcher.php');

$u=new UserManagement();
$iUserId=$u->getUserId("pedant.dk");
$s=new Searcher($iUserId);
$s->search_OLD("test");
?>
