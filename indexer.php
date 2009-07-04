<?
 require_once("app/template.php"); 
 require_once("app/views/grid.php");

 head("Yet Another Search Engine");
 leftbar();
 ?>

 <div id="setting">
  <div id="indexer"></div>
 </div>

<?php
 grid("indexerFilter", "indexer", "{}");
 ?>

<?php
 bottom();
?>

