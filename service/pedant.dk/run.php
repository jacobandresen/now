<?php
 require_once('../../classes/Global.php'); 
 require_once('../../classes/Search/Framework.php');

 $s=new Yas("pedant_dk");

 $s->crawl();
 $s->index();
?>
