<?php
define("MAX_CONTENT_LENGTH",2000000);
define("MYSQL_HOST", "localhost");
define("MYSQL_USER", "yase");
define("MYSQL_PASSWORD", "yase");
define("MYSQL_DATABASE", "yase");
define("TMP_YASE", "/tmp/yase/");

require_once('Account.php');
require_once('Encoding.php');
require_once('URL.php');
require_once('Setting.php');
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
