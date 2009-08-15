<?php
class Setting extends Model{
    protected  $sTable = "setting";

    public function fetchArray() {
        if ( isset($_SESSION['account_id']) ) { 
            return ( Model::fetchArray(" WHERE account_id=".$_SESSION['account_id']." ORDER by id") );
        } else {
            return ( Model::fetchArray() );
        } 
   }
};

?>
