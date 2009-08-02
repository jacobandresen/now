<?
 require_once("../YASE/Framework.php"); 
 require_once("../template.php"); 
 require_once("app/views/grid.php");

 Template::login(); 
 Template::head();
 Template::leftbar();
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

