<?
require_once("class_autoload.php"); 
require_once("app/grid.php");

Template::admintop();
?>
<H1>Crawler filter for <?=$_SESSION['account_domain'];?></H1>

Edit this list with regular expressions that identifies pages to be skipped.
 <div id="crawler" class="setting">
</div>

<?php
grid("crawlerfilters", "crawler", "{}");
?>

<?php
Template::bottom();
?>


