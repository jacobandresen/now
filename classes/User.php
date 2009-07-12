<?php
class User {

  static function login($sLogin, $sPassword) {
    $res = mysql_query("SELECT login from user where login='".$sLogin."' and password='".$sPassword."'");
    $row = mysql_fetch_row($res);
    $sLoggedIn = $row[0];
    if ( isset($sLoggedIn) ){
      $_SESSION['login'] = $sLoggedIn; 
      return true;
    }else{
      return false;
    } 
  }

  static function getId($sUser){
    $res=mysql_query("SELECT id from user where login='".$sUser."'");
    $row=mysql_fetch_row($res);
    return($row[0]);
  }

  // list accounts associated with user
  function getAccounts() {
  }

};
?>
