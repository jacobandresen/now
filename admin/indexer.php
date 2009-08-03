<?
 require_once("../classes/YASE/Framework.php"); 
 require_once("../classes/Template.php"); 
 require_once("app/views/grid.php");

 Template::admintop();
 ?>

 <H1> Indexer filter for <?php print $_SESSION['account_domain'];?></H1>
 <br><br>
 Edit this list with regular expressions that identifies pages to be skipped during indexing.

<br><br>
 <div id="setting">
  <div id="indexer"></div>
 </div>

<?php
 grid("indexerfilters", "indexer", "{}");
 ?>

<?php
 Template::bottom();
?>

