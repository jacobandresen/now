<?
require_once("class_autoload.php");
require_once("app/grid.php");

Template::admintop();
?>

<H1>Body filter </h1>
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
