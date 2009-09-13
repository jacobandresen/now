<?php
require_once("global.php");
require_once("../classes/YASE/Framework.php");
require_once("../classes/JobDaemon.php");

$jd=new JobDaemon();

$jd->clear(1);

$jd->schedule(1, "crawler", date('Y-m-d H:i:s'));
$jd->schedule(1, "indexer", date('Y-m-d H:i:s')); 

print_r($jd->listPending(1));
//$jd->executePending(1);
//print_r($jd->listPending(1));
?>
