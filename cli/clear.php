<?php
//2011, Jacob Andresen <jacob.andresen@gmail.com>
require_once("YASE/Framework.php");
mysql_query("delete from account");
mysql_query("delete from collection");
mysql_query("delete from document");
mysql_query("delete from facet");
?>
