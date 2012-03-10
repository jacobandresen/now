<?php
require_once("configuration.php");
require_once("../main/Framework.php");

mysql_query("delete from collection_domain") or die(mysql_error());
mysql_query("delete from document") or die(mysql_error());
mysql_query("delete from collection") or die(mysql_error());
mysql_query("delete from field") or die(mysql_error());
mysql_query("delete from token") or die(mysql_error());
mysql_query("delete from account") or die (mysql_error());

?>
