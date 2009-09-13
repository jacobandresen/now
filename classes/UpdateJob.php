<?php

class UpdateJob implements IJob {
    public function execute($iAccountID) {
        set_time_limit(0);
        $c = new Crawler($iAccountID);
        $c->start();
        $i = new Indexer($iAccountID);
        $i->start(); 
    }
};
?>
