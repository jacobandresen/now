<?
 require_once("../YASE/Framework.php");
 require_once("../template.php"); 
 require_once("app/views/grid.php");

 Template::login();
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
