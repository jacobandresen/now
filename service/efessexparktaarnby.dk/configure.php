<?php
 require_once('../../classes/Global.php'); 
 require_once('../../classes/Search/Framework.php');

 mysql_query("INSERT INTO user(login,password,level_limit,crawl_limit) values('efessexparktaarnby_dk', 'test', 200, 100000)");

 //setup
 $s=new Yas("efessexparktaarnby_dk");
 
 $s->addDomain("www.efessexparktaarnby.dk");
 $s->addCrawlSkip("feed"); 
 $s->addCrawlSkip("xmlrpc");
 $s->addCrawlSkip("print");
 $s->addCrawlSkip("respond"); 
 $s->addCrawlSkip("comment"); 
 $s->addCrawlSkip("css");
 $s->addCrawlSkip("bmp");
 $s->addCrawlSkip("jpg");
 $s->addCrawlSkip("zip");
 $s->addCrawlSkip("xml"); 
 

 $s->setIndexDomain("efessexparktaarnby.dk"); 
 $s->addIndexSkip("page");
 $s->addIndexSkip("tag");
 $s->addIndexSkip("xml");  
 $s->addIndexSkip("category");

?>
