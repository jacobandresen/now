<?php
require_once("../php/YASE/Framework.php");
mysql_query("delete from account");
mysql_query("delete from collection");
mysql_query("delete from document");
mysql_query("delete from facet");
?>
