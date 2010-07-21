<?php
require_once('Configuration.php');
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
require_once('Collection.php');

mysql_connect(MYSQL_HOST, MYSQL_USER, MYSQL_PASSWORD) or die(mysql_error());
mysql_select_db(MYSQL_DATABASE) or die(mysql_error());

function login($userName, $password)
{
  $res = mysql_query("SELECT id from account where username='".$userName."' and password='".$password."'");
  $row = mysql_fetch_array($res);
  $accountId = $row[0];
  if ( isset($acocuntId) ){
    return new Account($accountId);
  }else{
    throw new Exception("login failed"); 
  }
}

function createAccount($userName, $password, 
		       $firstName, $lastName)
{
  try{ 
      mysql_query("BEGIN"); 
      mysql_query("INSERT INTO account(username, password, first_name, last_name) VALUES('$userName','$password', '$firstName', '$lastName')");
      $loginId = login($userName, $password);
      createDefaultAccountSettings($loginId);
      mysql_query("COMMIT");
    }catch(Exception $herr){
 	print "account creation failed :".mysql_error();
        mysql_Query("ROLLBACK"); 
    }
}

function deleteAccount($accountId) 
{
  mysql_query("DELETE FROM account where id=$accountId");
}

function createDefaultAccountSettings($accountId) 
{
  $setting = new Setting("crawler", $accountId);
  $setting->set("crawl_limit", "1500");
  $setting->set("level_limit", "15"); 
}
?>
