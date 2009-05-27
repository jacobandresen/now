<?php
 require_once('UserManagement.php'); 
 require_once('Indexer.php');
 require_once('Crawler.php');
 require_once('Searcher.php');
 require_once('HTTPClient.php');
 require_once('Paging.php');

class Yase{ 
 
 protected $iAccountId; 

 protected $sAction;
 protected $sName;
 protected $sPassword;
 protected $sAccount;

 public $oUserManagement;
 
 public $oIndexer;
 public $oCrawler;
 public $oSearcher;

 public function __construct($sAccount){
   
   $this->oUserManagement = new UserManagement();
   $this->iAccountId      = $this->oUserManagement->getAccountId($sAccount);
   
   $this->oIndexer       = new Indexer($this->iAccountId);
   $this->oCrawler       = new Crawler($this->iAccountId); 
   $this->oSearcher      = new Searcher($this->iAccountId); 
 
   $this->getParameters();
 }

 public function getParameters(){
   if(isset($_REQUEST['name'])) {
     $this->sName         =$_REQUEST['name'];
   }		
   if(isset($_REQUEST['account'])) {
     $this->sAccount         =$_REQUEST['account'];
   }		
   if(isset($_REQUEST['password'])){
     $this->sPassword     =$_REQUEST['password'];
   } 
   if(isset($_REQUEST['action'])) {
     $this->sAction       =$_REQUEST['action'];
   }
   if(isset($_REQUEST['query'])) {
     $this->sQuery        =$_REQUEST['query']; 
   }
 }

 public function addCrawlFilter( $sName, $sValue ) {
   $this->oCrawler->filterSettings->put( $sName , $sValue, "regex");
 }


 public function crawl(){
   $domains = $this->oUserManagement->getDomains($this->iAccountId);
   print "START CRAWL:http://".$domains[0]."\r\n"; 
   $this->oCrawler->crawler("http://".$domains[0], 0, "http://".$domains[0]);
 }

 public function index(){
   $this->oIndexer->index();
 }

 public function search($sQuery, $iPage){
   $this->oSearcher->search($sQuery);
 } 
  public function page($sQuery, $iPage) {
    $oPaging = new Paging("yase.php?account=".$this->sAccount."&query=".$sQuery);
    $sQuery = utf8_decode($sQuery);
    $iTotal = $this->oSearcher->iSearch($sQuery); 
    
    if(!isset($_REQUEST['page']) || $_GET['page'] < 1){
      $iPage = 1;
    }
    $aRes = $this->oSearcher->aSearch($sQuery, $iPage);
    $iPages = (int) ((($iTotal-1)/$this->oSearcher->iLimit))+1;
    print '<div class="summary_info">The search for  <b>'.$sQuery.'</b> returned <b>'.$iTotal.'</b> results </div>';
    print '<div class="navigation">';
    $oPaging->sNavigationFloat($iPage, $iPages, 'account='.$this->sName.'&query='.$sQuery, $this->oSearcher->iLimit );
    print '</div>';
    foreach ($aRes as $oRes){
      print '<div class="title"><a href="'.$oRes->sUrl.'" target="_parent">'.$oRes->sTitle.'</a></div>';
      print '<div class="content">'.$oRes->sContent.'</div>';
    }
    print '<div class="navigation">';
    $oPaging->sNavigationFloat($iPage, $iPages, '&query='.$sQuery, $this->oSearcher->iLimit );
    print '</div>';
  }
};
?>


