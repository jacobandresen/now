<?php
require_once ('../classes/Global.php');
require_once ('../classes/UserManagement.php');
require_once ('../classes/HTTPClient.php');
require_once ('../classes/Crawler.php');


$u = new UserManagement();
$iUserId = $u->getUserId("pedant.dk");

$c = new Crawler($iUserId);
$c->crawl("http://pedant.dk", 0, "http://pedant.dk");

?>
