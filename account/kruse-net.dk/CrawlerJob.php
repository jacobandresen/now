<?php
require_once ('../../classes/Global.php');
require_once ('../../classes/Yase.php');

$y = new Yase("kruse-net.dk");
$y->oCrawler->reset();
$y->oCrawler->filterSettings->deleteAll();

$y->addCrawlFilter("javascript", "javascript\:");
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
$y->addCrawlFilter("admin", "\/admin");
$y->addCrawlFilter("tracker", "\/tracker");
$y->addCrawlFilter("dotproject", "\/dotproject");
$y->addCrawlFilter("magicdb", "\/magicdb");
$y->addCrawlFilter("gallery", "\/gallery");
$y->addCrawlFilter("pics", "\/pics");
$y->addCrawlFilter("slides", "\/slides");
$y->addCrawlFilter("viewforum", "\/viewforum");
$y->addCrawlFilter("profile", "\/profile");
$y->addCrawlFilter("search", "\/search\.php");
$y->addCrawlFilter("saelges", "saelges");
$y->addCrawlFilter("trackback", "\/trackback");
$y->crawl();

?>
