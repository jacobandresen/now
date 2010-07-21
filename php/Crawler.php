<?php

class Crawler
{
  private $account;

  private $level;
  private $found;
  private $crawled;
  private $process;
  private $httpClient;

  public function __construct($accountId)
  {
    $this->found=array();
    $this->crawled=array();
    $this->process=array();

    $this->account = new Account($accountId);
    
    //$this->domain = $this->setting->get('domain');

    $this->httpClient = new HTTPClient($this->domain);
  }

  public function start()
  {
    $startUrl = "http://".$this->domain;

    if($this->shouldCrawl($startUrl)){
      mysql_query("delete from document where account_id='".$this->accountId."'");
      $this->crawl( $startUrl, 0 , $startUrl);
    } else {
      print "failed to start crawl \r\n";
    }
  }

  public function crawl($url, $level, $parent)
  {
    print "crawl [$level] - $url \r\n";

    $document = $this->httpClient->getDocument($url);
    $document->level = $level;

    if ($document->contentType=="application/pdf") {
      $p=new PDFRobot($this->accountId);
      $document->content = $p->clean($document);
      $document->content = htmlentities($document->content,ENT_QUOTES);

      array_push($this->crawled, $url);
      return $document->save($this->account->id);
    } else {

      if (!$document->shouldCrawl()) {
        array_push( $this->found, $url); //skip document
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
          array_push($this->found, $link);
          array_push($this->process, $link);
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
    if ($this->inDomain($url) ==false) {
      array_push( $this->crawled, $url); //skip document
      return false;
    }
    if ($this->level > $this->account->setting->maxLevel ||
       count($this->crawled)>$this->crawlLimit||
       URL::filter($this->account->id, $url, "crawlerfilter")){ 
      array_push( $this->crawled, $url); //skip document
      return false;
    }
    return true;
  }
  
  //TODO: we should be able to allow several domains (use collection_in_domain)
  private function inDomain($url) {
    $host = URL::extractHost($url);
    $domain = str_replace("www.", "", $this->domain);
    return (strpos($host,$domain)!==false);
  }
};
?>
