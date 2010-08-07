<?php
class Account 
{
  public $id; 
  public $userName; 
  public $firstName;
  public $lastName; 

  public $collections;  

  public function __construct()
  {
  } 

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

 //   Collection::create( (object) array('ownerId' => $a->id));
 
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

    $a->collections	= Collection::read( (object) array('ownerId'=> $a->id));

    return $a; 
  } 

  public static function update($data) 
  {
 //   $SQL = "UPDATE account where id=".$data->id." set username='".$data-
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
      throw (new Exception("login failed for user ".$userName." with password ".$password)); 
    }
  }

}
?>
