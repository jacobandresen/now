<?
 require_once("app/template.php"); 
 require_once("app/views/grid.php");

 head("Yet another search engine");
 leftbar();
 ?>

 <div id="setting">
  <div id="crawler"></div>
 </div>

<?php
 grid("crawlerFilter", "crawler", "{}");
 ?>

<?php
 bottom();
?>

