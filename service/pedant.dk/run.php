<?php
 require_once('../../classes/Global.php'); 
 require_once('../../classes/HTTPClient.php'); 
 require_once('../../classes/Search/Framework.php');

 $s=new Yas();

 $s->login("pedant_dk", "test");
 $s->setup(); 
 $s->crawl();
 $s->index();
?>
