<?
 require_once("app/template.php"); 
 require_once("app/views/setting.php");

 head("Yet another search engine");
 
 ?>

 <dig id="setting">
  <div id="crawler"></div>
  <div id="indexer"></div>
 </div>
<?php
 settingGrid("crawler", "filter", "crawler");
 settingGrid("indexer", "filter", "indexer");
 ?>

<?php
 bottom();
?>

