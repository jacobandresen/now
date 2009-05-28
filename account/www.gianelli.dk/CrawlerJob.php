<?php
require_once ('../../classes/Global.php');
require_once ('../../classes/Yase.php');


$y = new Yase("www.gianelli.dk");

$y->oCrawler->reset();
$y->addCrawlFilter("print", "print");
$y->addCrawlFilter("pdf", "\.pdf");
$y->addCrawlFilter("ppt", "\.ppt");
$y->addCrawlFilter("jpeg", "\.jpeg");
$y->addCrawlFilter("jpg", "\.jpg");
$y->addCrawlFilter("gif", "\.gif");
$y->addCrawlFilter("zip", "\.zip");
$y->addCrawlFilter("feed", "feed");
$y->addCrawlFilter("css", "\.css");
$y->addCrawlFilter("xml", "\.xml");
$y->addCrawlFilter("xmlrpc", "xmlrpc");
$y->addCrawlFilter("respond", "\#respond");
$y->addCrawlFilter("comment", "\#comment");
$y->addCrawlFilter("war", "\.war");

$y->crawl();

?>
