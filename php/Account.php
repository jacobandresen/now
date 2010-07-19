<?php
class Account 
{
   public static function create($userName, $password, $firstName, $lastName)
  {
    mysql_query("INSERT INTO ACCOUNT(username, password, first_name, last_name) VALUES('$userName','$password', '$firstName', '$lastName')");
  }


  public static function login($userName, $password)
  {
    $res = mysql_query("SELECT id from login where username='".$userName."' and password='".$password."'");
    $row = mysql_fetch_array($res);
    $userId = $row[0];
    if ( isset($loginId) ){
      return $loginId;
    }else{
      return -1;
    }
  }

  public static function delete($accountId) 
  {


  }

}
?>
