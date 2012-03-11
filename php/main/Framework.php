<?php
require_once('Account.php');
require_once('Collection.php');
require_once('CollectionDomain.php');
require_once('Encoding.php');
require_once('URL.php');
require_once('HTTPClient.php');
require_once('Document.php');
require_once('Crawler.php');
require_once('Indexer.php');
require_once('Searcher.php');
require_once('HTMLRobot.php');
require_once('PDFRobot.php');

$conn = pg_connect("host=localhost user=jacob dbname=jacob");
?>
