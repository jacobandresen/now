<?php

/**
 * start a YASE_Crawler for the given account
 */
class CrawlerJob implements IJob {
    public function execute($iAccountID) {
        set_time_limit(0);
        $c = new YASE_Crawler($iAccountID);
        $c->start();
    }
};
?>
