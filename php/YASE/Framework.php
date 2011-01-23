<?php
//2011, Jacob Andresen <jacob.andresen@gmail.com>
require_once('Configuration.php');
require_once('Account.php');
require_once('Collection.php');
require_once('Domain.php');
require_once('Encoding.php');
require_once('URL.php');
require_once('HTTPClient.php');
require_once('Document.php');
require_once('Crawler.php');
require_once('Indexer.php');
require_once('Searcher.php');
require_once('HTMLRobot.php');
require_once('PDFRobot.php');
require_once('Response.php');
require_once('JSONApplication.php');

mysql_connect(MYSQL_HOST, MYSQL_USER, MYSQL_PASSWORD) or die(mysql_error());
mysql_select_db(MYSQL_DATABASE) or die(mysql_error());
?>
