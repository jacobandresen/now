<?php
require_once ('../../classes/Global.php');
require_once ('../../classes/Yase.php');


$y = new Yase("korpen.se");


$y->addCrawlFilter("print", "print");
$y->addCrawlFilter("pdf", "\.pdf");
$y->addCrawlFilter("ppt", "\.ppt");
$y->addCrawlFilter("jpeg", "\.jpeg");
$y->addCrawlFilter("jpg", "\.jpg");
$y->addCrawlFilter("gif", "\.gif");
$y->addCrawlFilter("zip", "\.zip");
$y->addCrawlFilter("doc", "\.doc");


$y->crawl();
?>
