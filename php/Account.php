<?php
class Account 
{
  public $id; 
  public $collections;

  public function __construct($accountId)
  {
    $this->id = $accountId; 
    $this->collections = array(); 
    $this->readCollections();
  }

  public function login($userName, $password)
  {
    $res = mysql_query("SELECT id from account where username='".$userName."' and password='".$password."'") or die(mysql_error());
    $row = mysql_fetch_array($res);
    $accountId = $row[0];
    if ( isset($accountId) ){
      return (new Account($accountId));
    }else{
      throw (new Exception("login failed for user ".$userName." with password ".$password)); 
    }
  }

  public static function create ($userName, $password, $firstName, $lastName)
  {
    $sql = "INSERT INTO account(username, password, first_name, last_name) VALUES('$userName','$password', '$firstName', '$lastName')";
    mysql_query($sql) or die(mysql_error()); 
  }

  public static function delete($accountId) 
  {
    mysql_query("DELETE FROM account where id=$accountId");
  }

  private function readCollections ( ) 
  {
    $colids = array();
    $sql = "SELECT id from collection where owner_id=".$this->id;
    $res = mysql_query($sql) or die( mysql_error());
    while ($row = mysql_fetch_array($res, MYSQL_NUM))  
    {
      array_push($colids, $row[0]); 
    }

    foreach ($colids as $colid) 
    {
      array_push($this->collections, Collection::retrieve($colid));
    }
  }

}
?>
