<?php

session_start();

class Crawlerfilter extends REST_Model{
  protected $sTable  = "setting";
  protected $sSection = "crawlerfilter";
 
  public function fetchArray() {
   if (isset($_SESSION['account_id'])) { 
   return ( parent::fetchArray(" WHERE section='crawlerfilter' AND account_id='".$_SESSION['account_id']."' ORDER by id") );
    }
   }
 
  public function post() {
    if ( isset($_SESSION['account_id'])) {
      $this->iAccountID = $_SESSION['account_id']; 
      parent::post();
    }
  }
};

?>
