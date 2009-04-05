<?php

//NOTE:access to this class should be protected
// (E.g: urls using this should have at least have BASIC authentication)
class UserManagement {

 function getUserId($sUser){
    $res=mysql_query("SELECT id from user where login='".$sUser."'");
    $row=mysql_fetch_row($res);
    return($row[0]);
 }

 function getDomains($iUserId){
  $aDomain=array();	 
  $res = mysql_query("SELECT urlbase from domain where user_id='".$iUserId."'");
  while($row = mysql_fetch_array($res)){
     array_push($aDomain, $row['urlbase'] );
  }
  return($aDomain);
 }

 function getDomainId($sUser, $sDomain){ 
   $userid=$this->getUserId($sUser);
   $res=mysql_query("SELECT id from domain where user_id='".$userid."' and urlbase='".$sDomain."'") or die(mysql_error());
   $row=mysql_fetch_row($res);
   return($row[0]); 
 }

 //admin or specific user rights
 function addDomain($sUser, $sDomain){
    $res=mysql_query("SELECT id from user where login='".$sUser."'");
    if($row=mysql_fetch_row($res)){
     	mysql_query("INSERT INTO domain(user_id,urlbase) values('".$row[0]."','".$sDomain."')");// or die(mysql_error());
    }else{
    print "not found";
    } 	    
  }	

 //should require admin rights 
  function addUser($sUser, $sPassword, $sDomain){
    mysql_query("INSERT INTO user(login,password,level_limit,crawl_limit) values('".$sUser."','".$sPassword."','100','5000')");// or die(mysql_error());

   $this->addDomain($sUser, $sDomain); 
    print "added $sUser \r\n"; 
  }
};

?>

