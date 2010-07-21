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
    mysql_query("insert into account_setting(account_id, setting_name, name, value) values('".$this->accountId."','".$this->settingName."','".$name."','".$value."');") or die ("setting failed:".mysql_error());
  }
  
  public function get ($name)  
  {
    $res = mysql_query("select value from account_setting where account_id='".$this->accountId."' and setting_name='".$this->settingName."' and name='".$name."'") or die (mysql_error());
    $row = mysql_fetch_array($res);
    return $row[0];     
  }
};
?>
