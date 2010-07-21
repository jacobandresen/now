<?php
class Account 
{
  public static function login($userName, $password)
  {
    $res = mysql_query("SELECT id from account where username='".$userName."' and password='".$password."'");
    $row = mysql_fetch_array($res);
    $loginId = $row[0];
    if ( isset($loginId) ){
      return $loginId;
    }else{
      return -1;
    }
  }

  public static function create($userName, $password, $firstName, $lastName)
  {
    try{ 
      mysql_query("BEGIN"); 
      mysql_query("INSERT INTO account(username, password, first_name, last_name) VALUES('$userName','$password', '$firstName', '$lastName')");
      $loginId = Account::login($userName, $password);
      Account::createDefaultSettings($loginId);
      mysql_query("COMMIT");
    }catch(Exception $herr){
 	print "account creation failed :".mysql_error();
        mysql_Query("ROLLBACK"); 
     }
  }

  public static function delete($accountId) 
  {
    mysql_query("DELETE FROM account where id=$accountId");
  }

  private static function createDefaultSettings($loginId) 
  {
   $setting = new Setting("crawler", $loginId);
   $setting->set("crawl_limit", "1500");
   $setting->set("level_limit", "15"); 
  }

}
?>
