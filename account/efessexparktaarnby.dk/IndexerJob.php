<?php
require_once ('../../classes/Global.php');
require_once ('../../classes/Framework.php');

$y = new Yase("efessexparktaarnby.dk");
$y->oIndexer->reset();
$y->index();

?>
