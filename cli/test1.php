<?php
 
 require_once('YASE/Framework.php');
 require_once('REST/Model.php'); 
 require_once('app/models/Crawlerfilter.php');
 require_once('app/models/Indexerfilter.php');

 $s = new Paging(1, "search.php");
 $c = new Crawlerfilter();
 session_start();
 $_SESSION['account_id']=1;

 $c->sName="pdf";
 $c->sValue="\.pdf";
 $c->post();

 $i = new Indexerfilter();
 $i->sName="jpg";
 $i->sValue="\.jpg";
 $i->post();

?>
