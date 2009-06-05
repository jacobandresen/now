<?php
require_once ('../../classes/Global.php');
require_once ('../../classes/Yase.php');

$y = new Yase("www3.swehockey.se");

$y->oIndexer->reset();

$y->index();


?>
