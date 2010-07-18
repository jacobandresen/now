<?php

class Setting 
{
  private $settingName;
  private $accountId;

  public function __construct($settingName, $accountId)
  {
    $this->accountId=$accountId;
    $this->settingName=$settingName;
  }

  public function set ($name, $value)
  {
    mysql_query("insert into account_setting(account_id, setting_name, name, value) values('".$this->accountId."','".$this->settingName."','".$name."','".$value."');");
  }
  
  public function get ($name)  
  {
    $res = mysql_query("select value from account_setting where id='".$accountId."' setting_name='".$settingName."' and name='".$name."'");
    $row = mysql_fetch_array($res);
    return $row[$name];     
  }
};
?>
