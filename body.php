<?
 require_once("app/template.php"); 
 require_once("app/login.php"); //this page requires a login 
 require_once("app/views/grid.php");

 head("Yet Another Search Engine");
 leftbar();
 ?>

 <H1>Body filter </h1>

 <br><br>
  Identify regular expressions that can be used to extract content from known page types 

<br><br>

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

