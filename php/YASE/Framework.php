<?php
//2011, Jacob Andresen <jacob.andresen@gmail.com>
//NOTE: YASE assumes that you have the settings from configuration.php.example
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

mysql_connect(MYSQL_HOST, MYSQL_USER, MYSQL_PASSWORD) or die(mysql_error());
mysql_select_db(MYSQL_DATABASE) or die(mysql_error());
?>
