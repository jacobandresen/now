<?php

class Account {
  public $iId; 
  public $sDomain; 
  public $iLevelLimit;
  public $iCrawlLimit; 

  public function getId($sDomain){
    $res = mysql_query("select id from account where domain='".$sDomain."';");
    $row = mysql_fetch_array($res);
    return($row['id']);
  }

  public function getDomain($iID){
    $sSQL="select domain from account where id='".$iID."';";
    $res = mysql_query($sSQL); 
    $row = mysql_fetch_row($res);
    return($row[0]);
  }
};
?>
