<?php

class Setting extends REST_Model implements IModel{
  protected  $sTable = "setting";

  public function fetchArray() {
    if ( isset($_REQUEST['account_id']) ) { 
    return ( REST_Model::fetchArray(" WHERE account_id=".$_REQUEST['account_id']." ORDER by id") );
    } else {
     return ( REST_Model::fetchArray() );
     } 
   }
};

?>
