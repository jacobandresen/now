<?php
require_once ('../classes/Global.php');
require_once ('../classes/UserManagement.php');
require_once ('../classes/HttpClient.php');
require_once ('../classes/search/Crawler.php');


$u = new UserManagement();
$iUserId = $u->getUserId("pedant.dk");

$c = new Crawler($iUserId);
$c->bCrawl("http://pedant.dk", 0, "http://pedant.dk");

?>
