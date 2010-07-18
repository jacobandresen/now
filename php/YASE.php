<?php
define("MAX_CONTENT_LENGTH",2000000);
define("MYSQL_USER", "jacob");
define("MYSQL_PASSWORD", "jacob");
define("MYSQL_DATABASE", "jacob");
define("TMP_YASE", "/tmp/yase/");

require_once('Encoding.php');
require_once('URL.php');
require_once('HTTPClient.php');
require_once('Document.php');
require_once('Crawler.php');
require_once('Indexer.php');
require_once('Searcher.php');

require_once('HTMLRobot.php');
require_once('PDFRobot.php');

class YASE
{
  public function __construct ()
  {
    mysql_connect("localhost", MYSQL_USER, MYSQL_PASSWORD) or die(mysql_error());
    mysql_select_db(MYSQL_DATABASE) or die(mysql_error());
  }

  public function search($domain,$query)
  {
    $searcher=new Searcher($this->getAccountId($domain));
    return $searcher->search($query, 0);
  }

  public function crawl($domain)
  {
    $crawler=new Crawler($this->getAccountId($domain));
    $crawler->start();
  }

  public function index($domain)
  {
    $indexer=new Indexer($this->getAccountId($domain));
    $indexer->clear();
    $indexer->index();
  }

  public function login($login, $password)
  {
    $res = mysql_query("SELECT id from user where login='".$login."' and password='".$password."'");
    $row = mysql_fetch_row($res);
    $userId = $row[0];
    if ( isset($userId) ){
      return $userId;
    }else{
      return -1;
    }
  }

  public function addUser($user, $password)
  {
    mysql_query("INSERT INTO user(login,password) values('".$user."','".$password."')") or die(mysql_error());
  }

  public function getUserId($user)
  {
    $res=mysql_query("SELECT id from user where login='".$user."'");
    $row=mysql_fetch_row($res);
    return($row[0]);
  }

  public function addAccount($login, $domain)
  {
    $userId=$this->getUserId($login);
    mysql_query("INSERT INTO account(user_id, domain, level_limit, crawl_limit) values($userId, '$domain', 15, 5000);") or die (mysql_error());
  }

  public function getAccountId($domain){
    $res = mysql_query("select id from account where domain='".$domain."';");
    $row = mysql_fetch_array($res);
    return($row['id']);
  }

  public function getAccountDomain($id){
    $SQL="select domain from account where id='".$id."';";
    $res = mysql_query($SQL);
    $row = mysql_fetch_row($res);
    return($row[0]);
  }
};
?>