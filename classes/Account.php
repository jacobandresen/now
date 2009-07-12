<?php
class Account {
  public $iId; 
  public $sDomain; 

  public function getId($sDomain){
    $res = mysql_query("select id from account where domain='".$sDomain."';");
    $row = mysql_fetch_array($res);
    return($row['id']);
  }
};
?>
