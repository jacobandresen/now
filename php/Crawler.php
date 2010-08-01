<?php

//TODO: separate crawlerfilter table
class Crawler extends Collection
{
  public $level;
  public $processURLs;
  public $seenURLs;
  public $crawledURLs;
  public $httpClient;

  public function __construct($collectionId)
  {
    Parent::__construct($collectionId);	  
    $this->prepare();
  }

  private function prepare()
  {
    $this->processURLs=array();
    $this->seenURLS=array(); 
    $this->crawledURLs=array();

    $this->httpClient = new HTTPClient($this->domains[0]);
  } 

  public function start()
  {
    $startUrl = "http://".$this->domain;

    if($this->shouldCrawl($startUrl)){
      mysql_query("delete from document where account_id='".$this->account->id."'");
      $this->crawl( $startUrl, 0 , $startUrl);
    } else {
      $this->log("failed to start crawl");
    }
  }

  public function crawl($url, $level, $parent)
  {
    $this->log("crawl [$level] - $url ");

    $document = $this->httpClient->getDocument($url);
    $document->level = $level;

    if ($document->contentType=="application/pdf") {
      $p=new PDFRobot($this->accountId);
      $document->content = $p->clean($document);
      $document->content = htmlentities($document->content,ENT_QUOTES);

      array_push($this->crawledURLs, $url);
      return $document->save($this->account->id);
    } else {

      if (!$document->shouldCrawl()) {
        array_push( $this->foundURLs, $url); //skip document
        return false;
      }

      preg_match_all("/\<a.*?(?:src|href)=\"([^\"]*?)\"/i",
                     $document->content, $matches);
      foreach ($matches[1] as $item) {
        $fullUrl = URL::expandUrl($item, $url);
        if ( $this->shouldCrawl($fullUrl) ) {
          $link = new Document();
          $link->url = $fullUrl;
          $link->level = $level+1;
          array_push($this->foundURLs, $link);
          array_push($this->processURLs, $link);
        }
      }

      $document->content = htmlentities($document->content,ENT_QUOTES);
      $document->save($this->account->id);
      array_push($this->crawled, $url);

      while($child=array_shift($this->process)){
       if($child->url!=""){
         if(!in_array($child->url, ($this->crawled))){
           $this->crawl($child->url, ($child->level), $url);
         }
       }
     }
   }
  }

  private function shouldCrawl($url)
  {
    if (in_array($url, $this->crawled)){
      return false;
    }
    if ($this->inAllowedDomains($url) ==false) {
      array_push( $this->crawled, $url); //skip document
      return false;
    }
    if ($this->level > $this->levelLimit ||
       count($this->crawled)>$this->pageLimit||
       URL::filter($this->ownerId, $url, "crawlerfilter")){ 
      array_push( $this->crawled, $url); 
      return false;
    }
    return true;
  }
};
?>
