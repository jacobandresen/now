<?php
//2011, Jacob Andresen <jacob.andresen@gmail.com>
require_once("configuration.php");
require_once("YASE/Framework.php");
mysql_query("delete from collection_domain") or die(mysql_error());
mysql_query("delete from document") or die(mysql_error());
mysql_query("delete from collection") or die(mysql_error());
mysql_query("delete from account") or die (mysql_error());
mysql_query("delete from token") or die(mysql_error());
?>
