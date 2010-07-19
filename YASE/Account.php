<?php
class YASE_Account 
{
  public static function createLogin($userName, $password)
  {
    mysql_query("INSERT INTO LOGIN(user_name, password) VALUES('$userName', '$password')");
  }

  public static function deleteLogin($userName, $password)
  {
    mysql_query("DELETE FROM LOGIN where user_name='".$userName."' and password='".$password."'"); 
  }

  public static function performLogin($userName, $password)
  {
    $res = mysql_query("SELECT id from login where user_name='".$userName."' and password='".$password."'");
    $row = mysql_fetch_row($res);
    $userId = $row[0];
    if ( isset($loginId) ){
      return $loginId;
    }else{
      return -1;
    }
  }

  public static function creaateAccount($userName, $password, $firstName, $lastName)
  {
    YASE_Account::createLogin($userName, $password);
    $loginId = YASE_Account::performLogin($userName, $password);
    mysql_query("INSERT INTO ACCOUNT(login_id, first_name, last_name) VALUES($loginId, '$firstName', '$lastName')");
  }
}
?>
