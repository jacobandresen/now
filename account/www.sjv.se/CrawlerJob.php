<?php
require_once ('../../classes/Global.php');
require_once ('../../classes/Yase.php');

$y = new Yase("www.sjv.se");

$y->oCrawler->reset();

$y->addCrawlFilter("js", "\.js");
$y->addCrawlFilter("print", "print");
$y->addCrawlFilter("pdf", "\.pdf");
$y->addCrawlFilter("PDF", "\.PDF");
$y->addCrawlFilter("ppt", "\.ppt");
$y->addCrawlFilter("PPT", "\.PPT");
$y->addCrawlFilter("jpeg", "\.jpeg");
$y->addCrawlFilter("JPEG", "\.JPEG");
$y->addCrawlFilter("jpg", "\.jpg");
$y->addCrawlFilter("JPG", "\.JPG");
$y->addCrawlFilter("gif", "\.gif");
$y->addCrawlFilter("GIF", "\.GIF");
$y->addCrawlFilter("zip", "\.zip");
$y->addCrawlFilter("feed", "feed");
$y->addCrawlFilter("xml", "\.xml");
$y->addCrawlFilter("XML", "\.XML");
$y->addCrawlFilter("css", "\.css");
$y->addCrawlFilter("CSS", "\.CSS");
$y->addCrawlFilter("xmlrpc", "xmlrpc");
$y->addCrawlFilter("respond", "\#respond");
$y->addCrawlFilter("comment", "\#comment");
$y->addCrawlFilter("war", "\.war");
$y->addCrawlFilter("audioselect", "audioselect");
$y->addCrawlFilter("antiselect", "antiselect");

$y->crawl();

?>
