<?php
require_once ('../../classes/Global.php');
require_once ('../../classes/Yase.php');

$y = new Yase("jci.dk");

$y->oCrawler->reset();
$y->addCrawlFilter("print", "print");

$y->crawl();
?>
