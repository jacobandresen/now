<?php
class Crawlerfilter extends Model{
    protected $sTable  = "setting";
    protected $sTablename = "crawlerfilter";
 
    public function fetchArray() {
        if (isset($_SESSION['account_id'])) { 
            return ( parent::fetchArray(" WHERE tablename='crawlerfilter' AND account_id='".$_SESSION['account_id']."' ORDER by id") );
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
