<?php
 
 require_once('global.php'); 
 require_once('../classes/YASE/Framework.php');
 require_once('../admin/app/lib/Model.php'); 
 require_once('../admin/app/models/Crawlerfilter.php');
 require_once('../admin/app/models/Indexerfilter.php');

 $s = new YASE_Paging(1, "search.php");
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
