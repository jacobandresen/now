<?php
require_once ('../../classes/Global.php');
require_once ('../../classes/UserManagement.php');
require_once ('../../classes/HTTPClient.php');
require_once ('../../classes/Searcher.php');

$u=new UserManagement();
$iUserId=$u->getUserId("johanbackstrom.se");
$s=new Searcher($iUserId);
$s->search("du");

?>
