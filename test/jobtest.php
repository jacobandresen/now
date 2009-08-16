<?php
require_once("global.php");
require_once("../classes/YASE/Framework.php");
require_once("../classes/JobDaemon.php");

JobDaemon::clear(1);
//JobDaemon::schedule(1, "crawler", $d1);
//JobDaemon::schedule(1, "indexer", $d1);
JobDaemon::schedule(2, "crawler", $d1);
JobDaemon::schedule(2, "indexer", $d1); 
JobDaemon::executePending(2);
?>
