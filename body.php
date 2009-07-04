<?
 require_once("app/template.php"); 
 require_once("app/views/grid.php");

 head("Yet Another Search Engine");
 leftbar();
 ?>

 <div id="setting">
  <div id="bodyfilter"></div>
 </div>

<?php
 grid("bodyfilter", "bodyfilter", "{}");
 ?>

<?php
 bottom();
?>





<?php
 bottom();
?>

