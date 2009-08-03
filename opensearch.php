<?php
 require_once("YASE/Framework.php");

 $s=new Searcher("1");
 foreach ($s->aSearch("java",0) as $res ){
  print($res->sUrl)."\r\n";
  print($res->sTitle)."\r\n"; 
 } 

?>
