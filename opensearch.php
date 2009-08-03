<?php
require_once("classes/YASE/Framework.php");

 //TODO: return results using opensearch (http://opensearch.org)
$s=new YASE_Searcher("1");
foreach ($s->aSearch("java",0) as $res ){
    print($res->sUrl)."\r\n";
    print($res->sTitle)."\r\n"; 
} 

?>
