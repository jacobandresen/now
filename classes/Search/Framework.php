<?php
 require_once('Indexer.php');
 require_once('Crawler.php');
 require_once('Searcher.php');


//engine facade
class Yas{ 
 
 protected $bLoggedIn; 
 protected $iCustomerId; 
 
 public $sAction;
 public $sLogin;
 public $sName;


 public $sPassword;
 public $oIndexer;
 public $oCrawler;

 public function loggedIn(){
   return($this->bLoggedIn);
 }

 public function __construct(){
   $this->getParameters();
 }

 public function setup(){
  $this->oIndexer       = new Indexer($this->iCustomerId);
  $this->oCrawler       = new Crawler($this->iCustomerId); 
 }

 public function getParameters(){
   $this->sLogin        =$_POST['login'];
   $this->sPassword     =$_POST['password'];
   $this->sAction       =$_POST['action'];
   $this->sTicket       =$_POST['ticket'];
   $this->sName         =$_POST['name'];
 
   $_SESSION['login']   =$this->sLogin;
   $_SESSION['action']  =$this->sAction;
 }
 

 public function addDomain($sDomain){
   $sql="insert into domain(user_id, base) values('".$this->iCustomerId."','".$sDomain."')";
   mysql_query($sql);
 }

//---index skips --
 public function getIndexSkip() {
   return ($this->oIndexer->updateSkipFilters());
 }
 public function addIndexSkip($filter){
   $this->oIndexer->addSkipFilter($filter);
 }
 public function delIndexSkip(){
   $this->oIndexer->delSkipIndexFilters();
  }


//--crawl skips --
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


