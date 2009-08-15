<?
require_once("class_autoload.php"); 
require_once("app/grid.php");

Template::admintop();
?>

<H1> Indexer filter for <?php print $_SESSION['account_domain'];?></H1>
Edit this list with regular expressions that identifies pages to be skipped during indexing.

<div id="indexer" class="setting"></div>

<?php
grid("indexerfilters", "indexer", "{}");
?>

<?php
Template::bottom();
?>

