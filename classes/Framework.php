<?php
 require_once('UserManagement.php'); 
 require_once('Indexer.php');
 require_once('Crawler.php');
 require_once('Searcher.php');
 
class Yase{ 
 
 protected $iCustomerId; 
 protected $sAction;
 protected $sName;
 protected $sPassword;

 protected $oUserManagement;
 protected $oIndexer;
 protected $oCrawler;
 protected $oSearcher;

 public function __construct($sUser){
   
   $this->oUserManagement = new UserManagement();
   $iCustomerId = $this->oUserManagement->getUserId($sUser);
   $this->iCustomerId    = $iCustomerId;
   
   $this->oIndexer       = new Indexer($iCustomerId);
   $this->oCrawler       = new Crawler($iCustomerId); 
   $this->oSearcher      = new Searcher($iCustomerId); 
   
   $this->getParameters();
 }

 public function getParameters(){
   $this->sName         =$_POST['name'];
   $this->sPassword     =$_POST['password'];
   $this->sAction       =$_POST['action'];
   $this->sQuery        =$_POST['query']; 
  }

 public function crawl(){
   $domains = $this->oUserManagement->getDomains($this->iCustomerId);
   $this->oCrawler->crawl($domains[0], 0, $domains[0]);
 }

 public function index(){
   $this->oIndexer->index();
 }

 public function search($sQuery){
   $this->oSearcher->search($sQuery);
 } 


};

?>


