<?php
class Account 
{
  public    $id; 
  protected $setting;
  protected $collections;

  public function __construct($accountId)
  {
    $this->id = $accountId; 
    $this->crawlerSetting = new Setting("crawler", $this->id);
  }

  private function readCollectionInformation( ) 
  {
  }

}
?>
