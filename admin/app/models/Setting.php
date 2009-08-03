<?php

class Setting extends REST_Model{
  protected  $sTable = "setting";

  public function fetchArray() {
    if ( isset($_SESSION['account_id']) ) { 
    return ( REST_Model::fetchArray(" WHERE account_id=".$_SESSION['account_id']." ORDER by id") );
    } else {
     return ( REST_Model::fetchArray() );
     } 
   }
};

?>
