<?php
include ('../../classes/Global.php');
include ('../../classes/Yase.php');

$y = new Yase("pedant.dk");

$y->oCrawler->reset();
$y->oCrawler->filterSettings->deleteAll();

$y->addCrawlFilter("pdf", "\.pdf");
$y->addCrawlFilter("ppt", "\.ppt");
$y->addCrawlFilter("jpg", "\.jpg");
$y->addCrawlFilter("png", "\.png");
$y->addCrawlFilter("gif", "\.gif");
$y->addCrawlFilter("zip", "\.zip");
$y->addCrawlFilter("feed", "feed");
$y->addCrawlFilter("css", "\.css");
$y->addCrawlFilter("xml", "\.xml");
$y->addCrawlFilter("xmlrpc", "xmlrpc");
$y->addCrawlFilter("respond", "\#respond");
$y->addCrawlFilter("comment", "\#comment");
$y->addCrawlFilter("war", "\.war");
$y->addCrawlFilter("javascript", "\.js");

$y->crawl();
?>
