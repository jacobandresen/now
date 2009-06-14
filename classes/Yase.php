<?php

require_once("Global.php");

function __autoload($class_name) {
  $path = str_replace("_", "/", $class_name);
  require_once $path.".php";
}

class Yase{ 

  public    $indexer;
  public    $crawler;
  public    $searcher;

  protected $accountID; 
  protected $userManagement;
 
  public function __construct($account){
    $this->userManagement = new UserManagement();
    $this->account			  = $account; 
    $this->accountID      = $this->userManagement->getAccountId($account);
    $this->indexer        = new Indexer($this->accountID);
    $this->crawler        = new Crawler($this->accountID); 
    $this->searcher       = new Searcher($this->accountID); 
  }

  public function crawl(){
    $domains = $this->userManagement->getDomains($this->accountID);
    $this->crawler->crawler("http://".$domains[0], 0, "http://".$domains[0]);
  }

  public function index(){
    $this->indexer->index();
  }

  public function search($query, $page){
    $this->searcher->search($query);
  } 

  public function page($query, $page) {
  
    //TODO: move this to Paging
    $paging = new Paging("search.php?account=".$this->account."&query=".$query);
    $total = $this->searcher->iSearch($query); 
    
    if(!isset($_REQUEST['page']) || $_GET['page'] < 1){
      $page = 1;
    }
   
    $results = $this->searcher->aSearch($query, $page);
    $pages = (int) ((($total-1)/$this->searcher->iLimit))+1;
   
    print '<div class="summary_info">The search for  <b>'.$query.'</b> returned <b>'.$iTotal.'</b> results </div>';
   
    print '<div class="navigation">';
   
    $paging->sNavigationFloat($page, $pages, 'account='.$REQUEST['account'].'&query='.$query, $this->searcher->iLimit );
    print '</div>';

    foreach ($results as $res){
      print '<div class="title"><a href="'.$res->sUrl.'" target="_parent">'.$res->sTitle.'</a></div>';
      print '<div class="content">'.$res->sContent.'</div>';
    }
    print '<div class="navigation">';
    $paging->sNavigationFloat($page, $pages, '&query='.$query, $this->searcher->iLimit );
   print '</div>';
  }

};
?>


