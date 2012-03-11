<?php
require_once("configuration.php");
require_once("../main/Framework.php");

pg_query("delete from collection_domain");
pg_query("delete from node");
pg_query("delete from document");
pg_query("delete from collection");
pg_query("delete from account");
?>
