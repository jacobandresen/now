<?php

class Indexerfilter extends REST_Model{
  protected $sTable  = "setting";
  protected $sSection = "indexerfilter";
 
  public function fetchArray() {
    if ( isset($_SESSION['account_id']) ) { 
    return ( parent::fetchArray(" WHERE section='".$this->sSection."' AND account_id=".$_SESSION['account_id']." ORDER by id") );
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
