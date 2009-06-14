<?php

class Setting extends Model{
  protected  $sTable = "setting";

  public function search() {
    if ( isset($_REQUEST['account_id']) ) { 
    return ( Model::fetchArray(" WHERE account_id=".$_REQUEST['account_id']." ORDER by id") );
    } else {
     return ( Model::fetchArray() );
     } 
   }
};

?>
