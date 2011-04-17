<?php
//2011, Jacob Andresen <jacob.andresen@gmail.com>
require_once("configuration.php");
require_once("YASE/Framework.php");
mysql_query("delete from token") or die("failed to delete tokens:"+mysql_error());;
mysql_query("delete from facet") or die("failed to delete facets:"+mysql_error());;
mysql_query("delete from document") or die("failed to delete documents:"+myql_error());
mysql_query("delete from collection") or die("failed to delete collections:" +mysql_error());
mysql_query("delete from account") or die("failed to delete accounts:"+mysql_error());
?>
