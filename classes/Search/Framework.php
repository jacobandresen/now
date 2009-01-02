<?php
 require_once('Indexer.php');
 require_once('Crawler.php');
 require_once('Searcher.php');

function login($sUserName, $sPassword) {
  $res = mysql_query("SELECT id from user where login='".$sUserName."' and password='".$sPassword."' ");
  if($row=mysql_fetch_array($res)){ 
    return($row['id']);
  }else{
    return(-1); 
  }
}

function reset_customer_state () {
  mysql_query("DELETE FROM user");
  mysql_query("DELETE from domain");
  mysql_query("DELETE from crawlskip");
  mysql_query("DELETE from indexskip");
}


class Yas{ 
 protected $iCustomerId; 
 
 public $oIndexer;
 public $oCrawler;
 
 public function __construct($sLogin){
  $res = mysql_query("select id from user where login='".$sLogin."'") or die(mysql_error());
  
  if($row=mysql_fetch_array($res)){
   $this->iCustomerId    = $row['id'] ; 
   $this->oIndexer       = new Indexer($this->iCustomerId);
   $this->oCrawler       = new Crawler($this->iCustomerId); 
   }else{
    die("failed to construct Administrator \r\n");
  }
 }

 public function addDomain($sDomain){
   $sql="insert into domain(user_id, base) values('".$this->iCustomerId."','".$sDomain."')";
   mysql_query($sql);
 }
 public function getIndexSkip() {
   return ($this->oIndexer->getSkipFilters());
 }
 public function addIndexSkip($filter){
   $this->oIndexer->addSkipFilter($filter);
 }
 public function delIndexSkip(){
   $this->oIndexer->delSkipIndexFilters();
  }

 public function addCrawlSkip($filter){
   $this->oCrawler->addSkipFilter($filter);
 }
 public function getCrawlSkip() {
   return ($this->oCrawler->getSkipFilters());
 } 

 public function setIndexDomain($sDomain){
  $this->oIndexer->setDomain($sDomain);
 }
 public function setIndexBodyFilter($sFilter){
  $this->oIndexer->addBodyFilter($sFilter);
 }

 public function crawl(){

   $this->oCrawler->clear(); 
   $res = mysql_query("SELECT base from domain where user_id='".$this->iCustomerId."'"); 
 while($row=mysql_fetch_array($res) ) { 
    $this->oCrawler->addFilter($row['base']); 
  
    //startup crawler 
    $sUrl="http://".$row['base'];
    $this->oCrawler->add($sUrl, $this->oCrawler->getUrl($sUrl), 0);
    array_push($this->oCrawler->aFound, $sUrl); 
    $this->oCrawler->bCrawl($sUrl, 0, $sUrl);
  } 
 }
 
 public function index(){
   $this->oIndexer->index();
  }
};

?>


