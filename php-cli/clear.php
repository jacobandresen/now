<?php
require_once("../php/Framework.php");
mysql_query("delete from account");
mysql_query("delete from collection");
mysql_query("delete from document");
mysql_query("delete from facet");
?>
