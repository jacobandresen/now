<?
 require_once("app/template.php"); 
 require_once("app/login.php"); //this page requires a login 
 require_once("app/views/grid.php");

 head("Yet Another Search Engine");
 leftbar();
 ?>

 <H1> Indexer filter </H1>
 
 <br><br>
 Edit this list with regular expressions that identifies pages to be skipped during indexing.

<br><br>

 <div id="setting">
  <div id="indexer"></div>
 </div>

<?php
 grid("indexerfilters", "indexer", "{}");
 ?>

<?php
 bottom();
?>

