<?php

class Account 
{
  public $id; 
  public $userName; 
  public $firstName;
  public $lastName; 

  public $collections;  

  public static function create ($data)
  {
    $SQL = "INSERT INTO account(username, password, first_name, last_name) VALUES('".$data->userName."','".$data->password."','".$data->firstName."','".$data->lastName."')";
    mysql_query($SQL) or die("create failed:".$SQL.mysql_error()); 
    
    $a = new Account(); 
    $a->id = mysql_insert_id();
    $a->userName = $data->userName;
    $a->password = $data->password;
    $a->firstName = $data->firstName;
    $a->lastName = $data->lastName;

    return $a; 
  }

  public static function read($data)
  {
    $SQL = "SELECT id,username,password,first_name,last_name from account";
    $res =  mysql_query($SQL) or die ("read failed:".$SQL.mysql_error());
    $row = mysql_fetch_array($res);
    
    $a = new Account(); 
    $a->id 		= $row[0];
    $a->userName 	= $row[1];
    $a->password	= $row[2];
    $a->firstName	= $row[3];
    $a->lastName 	= $row[4];

    $cids=array();
    $SQL = "SELECT id from collection where owner_id=".$a->id;
    $res = mysql_query($SQL) or die("read collections failed:".$SQL.mysql_error());
    
    while( $row = mysql_fetch_array($res)) {
      array_push($cids, $row[0]); 
    } 

    $a->collections = array();  
    foreach ($cids as $cid) { 
      array_push($a->collections, Collection::read( (object) array('id'=> $cid)));
    }
    return $a; 
  } 

  public static function update($data) 
  {
    $SQL = "UPDATE account where id=".$data->id." set username='".$data-userName."',password='".$data->password."',first_name='".$data->firstName."',last_name='".$data->lastName."'";
    mysql_query($SQL) or die ("Account update failed:".$SQL.mysql_error());
  }

  public static function destroy($accountId) 
  {
    mysql_query("DELETE FROM account where id=$accountId");
  }

  public function login($userName, $password)
  {
    $res = mysql_query("SELECT id from account where username='".$userName."' and password='".$password."'") or die(mysql_error());
    $row = mysql_fetch_array($res);

    $accountId = $row[0];
    
    if ( isset($accountId) ){
       return (Account::read((object) array("id"=>$accountId)));
    }else{
      throw (new Exception("login failed for user ".$userName)); 
    }
  }
}
?>
