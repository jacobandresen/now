<?php
session_start();

class UserManagement {

  function login($sLogin, $sPassword) {
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
  function checkAccess($iAccountId) {
    if($_SESSION['login']) { 
      $iUserId = $this->getUserId( $_SESSION['login'] ) ;
      $res = mysql_query("SELECT id from account where user_id='".$iUserId."'"); 
      $row = mysql_fetch_row($res);
      $iIDcheck= $row['id'];
      if ( $iIDcheck == $iAccountId) {
        return true;
      }else{
        return false; 
      }
    }
  }

  function clearAll () {
    mysql_query("DELETE  from account;") or die(mysql_error());
    mysql_query("DELETE  from user;") or die(mysql_error());
    print "clear! \r\n";  
  }

  function addUser($sUser, $sPassword, $sDomain){
    print "add user: $sUser \r\n"; 
    mysql_query("INSERT INTO user(login,password) values('".$sUser."','".$sPassword."')") or die(mysql_error());

    $iAccountId=$this->addDefaultAccount($sUser); 
    $this->addDomain($iAccountId, $sDomain); 
    print "added $sUser \r\n"; 
  }

  function getUserId($sUser){
    $res=mysql_query("SELECT id from user where login='".$sUser."'");
    $row=mysql_fetch_row($res);
    return($row[0]);
  }
 
 function addDefaultAccount($sUser) {
   $iUserId=$this->getUserId(sUser);
   mysql_query("INSERT INTO account(name,user_id,level_limit,crawl_limit) values('".$sUser."','".$iUserId."','100','5000')") or die(mysql_error());
   return($this->getDefaultAccount($sUser)); 
 }

 function getDefaultAccount($sUser) {
   $res= mysql_query("SELECT * from account where name='".$sUser."'") or die(mysql_error());;
   $row = mysql_fetch_row($res); 
   return($row[0]);   
  }

 function getAccountId($sAccount) {
   $res = mysql_query("SELECT id from account where name='".$sAccount."'") or die(mysql_error());;
   $row = mysql_fetch_row($res);
   return($row[0]);
 }

 function addDomain($iAccountId, $sDomain){
    print "add domain: $sDomain \r\n"; 
    mysql_query("INSERT INTO domain(account_id,name) values('".$iAccountId."','".$sDomain."')") or die(mysql_error());
 }	

 function getDomains($iAccountId){
   $aDomain=array();	 
   $res = mysql_query("SELECT name from domain where account_id='".$iAccountId."'");
   while($row = mysql_fetch_array($res)){
     array_push($aDomain, $row['name'] );
   }
   return($aDomain);
 }

 function getDomainId($sUser, $sDomain){ 
   $userid=$this->getUserId($sUser);
   $res=mysql_query("SELECT id from domain where account_id='".$iAccountId."' and name='".$sDomain."'") or die(mysql_error());
   $row=mysql_fetch_row($res);
   return($row[0]); 
 }

};

?>

