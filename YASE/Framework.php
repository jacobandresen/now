<?php
define("MAX_CONTENT_LENGTH",2000000);
define("MYSQL_HOST", "localhost");
define("MYSQL_USER", "jacob");
define("MYSQL_PASSWORD", "jacob");
define("MYSQL_DATABASE", "jacob");
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

class Framework 
{
  public function __construct ()
  {
    mysql_connect(MYSQL_HOST, MYSQL_USER, MYSQL_PASSWORD) or die(mysql_error());
    mysql_select_db(MYSQL_DATABASE) or die(mysql_error());
  }

  public function search($userName,$query)
  {
    $searcher=new YASE_Searcher($this->getAccountId($userName));
    return $searcher->search($query, 0);
  }

  public function crawl($userName)
  {
    $crawler=new YASE_Crawler($this->getAccountId($userName));
    $crawler->start();
  }

  public function index($userName)
  {
    $indexer=new YASE_Indexer($this->getAccountId($userName));
    $indexer->clear();
    $indexer->index();
  }

  public function login($userName, $password)
  {
    return YASE_Account::peformLogin($userName, $password);
  }

};
?>
