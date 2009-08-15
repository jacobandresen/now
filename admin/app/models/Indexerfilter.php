<?php
class Indexerfilter extends Model{
    protected $sTable  = "setting";
    protected $sTablename = "indexerfilter";
 
    public function fetchArray() {
        if ( isset($_SESSION['account_id']) ) { 
            return ( parent::fetchArray(" WHERE tablename='".$this->sSection."' AND account_id=".$_SESSION['account_id']." ORDER by id") );
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
