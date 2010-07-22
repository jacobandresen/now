<?php
class Account 
{
  public    $id; 
  protected $setting;
  protected $collections;

  public function __construct($accountId)
  {
    $this->id = $accountId; 
    $this->crawlerSetting = new Setting("crawler", $this->id);
  }

  public static function login($userName, $password)
  {
    $res = mysql_query("SELECT id from account where username='".$userName."' and password='".$password."'") or die(mysql_error());
    $row = mysql_fetch_array($res);
    $accountId = $row[0];
    if ( isset($accountId) ){
      return new Account($accountId);
    }else{
      throw new Exception("login failed for user ".$userName." with password ".$password); 
    }
  }

  public static function create ($userName, $password, $firstName, $lastName)
  {
    $sql = "INSERT INTO account(username, password, first_name, last_name) VALUES('$userName','$password', '$firstName', '$lastName')";
    print $sql;
    mysql_query($sql) or die(mysql_error()); 
    Account::createDefaultSettings(mysql_insert_id());
  }

  public function delete($accountId) 
  {
    mysql_query("DELETE FROM account where id=$accountId");
  }

  private function createDefaultSettings($accountId) 
  {
    $setting = new Setting("crawler", $accountId);
    $setting->set("crawl_limit", "1500");
    $setting->set("level_limit", "15"); 
  }

}
?>
