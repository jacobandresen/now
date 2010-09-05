<?php
class Crawler 
{
  public $collection;
  public $level;
  public $processURLs;
  public $seenURLs;
  public $crawledURLs;
  public $httpClient;

  public function __construct($params)
  {
    $this->collection = Collection::read($params); 
 
    if(!isset($this->collection)){
      print "failed to find collection for :\r\n";
      print_r($params);
    }
  
    $this->processURLs=array();
    $this->seenURLS=array(); 
    $this->foundURLs=array(); 
    $this->crawledURLs=array();
    $this->startUrl = $this->collection->startUrl;
   
    $this->httpClient = new HTTPClient($this->startUrl);
  }

  public function start()
  {
    if($this->shouldCrawl($this->startUrl)){
      mysql_query("delete from document where collection_id='".$this->collection->id."'");
      $this->crawl( $this->startUrl, 0 , $this->startUrl);
    } else {
      $this->collection->log("failed to start crawl");
    }
  }

  public function crawl($url, $level, $parent)
  {
    $this->collection->log("crawl [$level] - $url ");

    $document = $this->httpClient->getDocument($url);
    $document->level = $level;

    if ($document->contentType=="application/pdf") {
      $p=new PDFRobot($this->collection->ownerId);
      $document->content = $p->clean($document);
      $document->content = htmlentities($document->content,ENT_QUOTES);

      array_push($this->crawledURLs, $url);
      return $document->save($this->collection->id);
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
      $document->save($this->collection->id);
      array_push($this->crawledURLs, $url);

      while($child=array_shift($this->processURLs)){
       if($child->url!=""){
         if(!in_array($child->url, ($this->crawledURLs))){
           $this->crawl($child->url, ($child->level), $url);
         }
       }
     }
   }
  }

  private function shouldCrawl($url)
  {
    if (in_array($url, $this->crawledURLs)){
      return false;
    }
    if ($this->collection->inAllowedDomains($url) ==false) {
      array_push( $this->crawledURLs, $url); //skip document
      return false;
    }
    if ($this->level > $this->collection->levelLimit ||
       count($this->crawledURLs)>$this->collection->pageLimit||
       URL::filter($this->collection->ownerId, $this->collection->getDomainId($url), $url, "crawlerfilter")){ 
      array_push( $this->crawledURLs, $url); 
      return false;
    }
    return true;
  }

};
?>
