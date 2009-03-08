<?php
require_once ('../classes/Global.php');
require_once ('../classes/UserManagement.php');
require_once ('../classes/HttpClient.php');
require_once ('../classes/search/Searcher.php');


$s=new Searcher("pedant.dk");
$s->search_OLD("test");
?>
