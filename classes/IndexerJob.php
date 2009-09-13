<?php
class IndexerJob implements IJob 
{
    public function execute($iAccountID) {
        set_time_limit(0);
        $c = new Indexer($iAccountID);
        $c->start();
    }
};

?>
