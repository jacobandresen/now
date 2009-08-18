<?php
class IndexerJob implements IJob 
{
    public function execute($iAccountID) {
        set_time_limit(0);
        $c = new YASE_Indexer($iAccountID);
        $c->start();
    }
};

?>
