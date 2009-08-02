<?
 require_once("classes/Yase.php");
 require_once("app/template.php"); 
 require_once("app/login.php"); //this page requires a login 
 require_once("app/views/grid.php");

 Template::head();
 Template::leftbar();
 ?>

 <H1>Body filter </h1>
 <br><br>
<p>
  Identify regular expressions that can be used to extract content from known page types 
</p>
<p>
<i>NOTE: this page is not finished</i>
</p>
 <div id="setting">
  <div id="bodyfilter"></div>
 </div>

<?php
 grid("bodyfilter", "bodyfilter", "{}");
 ?>

<?php
 Template::bottom();
?>
