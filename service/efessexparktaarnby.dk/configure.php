<?php
 require_once('../../classes/Global.php'); 
 require_once('../../classes/HTTPClient.php'); 
 require_once('../../classes/Search/Framework.php');

 print "configure efessexparktaarnby.dk\r\n";
 mysql_query("INSERT INTO user(login,password,level_limit,crawl_limit) values('efessexparktaarnby_dk', 'test', 200, 10000)");

 //setup
 $s=new Yas("efessexparktaarnby_dk");
 $s->addDomain("efessexparktaarnby.dk");

 //add the following index skips for pedant.dk
 $s->setIndexDomain("efessexparktaarnby.dk"); 

?>
