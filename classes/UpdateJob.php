<?php

/**
 * Crawl and index 
 *  
 */
class UpdateJob implements IJob {
    public function execute($iAccountID) {
        set_time_limit(0);
        $c = new YASE_Crawler($iAccountID);
        $c->start();
        $i = new YASE_Indexer($iAccountID);
        $i->start(); 
    }
};
?>
