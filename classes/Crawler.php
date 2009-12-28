<?php
class Crawler
{
  private $accountId;
  private $domain;
  private $maxLevel;
  private $crawlLimit;

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

    $this->accountId=$accountId;
    $res = mysql_query('select level_limit,crawl_limit,domain from account where id="'.$accountId.'"');
    $row =  mysql_fetch_array($res);
    $this->maxLevel = $row['level_limit'] ;
    $this->crawlLimit = $row['crawl_limit'] ;
    $this->domain = $row['domain'];

    $this->httpClient = new HTTPClient($this->domain);
  }

  public function start()
  {
    mysql_query("delete from dump where account_id='".$this->accountId."'");
    $this->crawl( "http://".$this->domain, 0 , "http://".$this->domain);
  }

  public function crawl($url, $level, $parent)
  {
    if ($this->inDomain($url) ==false) {
      print "skip - 'not in domain' $url \r\n";
      array_push( $this->crawled, $url); //skip document
      return false;
    }
    if ($this->level > $this->maxLevel ||
       count($this->crawled)>$this->crawlLimit||
       in_array($url, $this->crawled) ||
       !(URL::filter($url, "crawlerfilter", $this->accountId))){ 
      print "skip - 'filtered' \r\n"; 
      array_push( $this->crawled, $url); //skip document
      return false;
    }
 
    print "crawl [$level] - $url \r\n";

    $document = $this->httpClient->getDocument($url);
    $document->level = $level;

    //default to HTML 
    if ($document->contentType=="application/pdf") {
      $p=new PDFFilter($this->accountId);
      $document->content = $p->filter($document);
      array_push($this->crawled, $url);
      $document->content = htmlentities($document->content,ENT_QUOTES);
      return $document->save($this->accountId);
    } else {

     if (!$document->shouldCrawl()) {
        array_push( $this->crawled, $url); //skip document
        return false;
      }

      //get HTML links 
      preg_match_all("/(?:src|href)=\"([^\"]*?)\"/i", 
                     $document->content, $matches);
      foreach ($matches[1] as $item) {
        $fullUrl = URL::expandUrl($item, $url);
        if ( (!in_array($fullUrl, $this->found) &&
          URL::filter($url, "crawlerfilter", $this->accountId))) { 
          $link = new Document();
          $link->url = $fullUrl;
          $link->level = $level+1;
          array_push($this->found, $link);
          array_push($this->process, $link);
        }
      }
   
      $document->content = htmlentities($document->content,ENT_QUOTES);
      $document->save($this->accountId);
      array_push($this->crawled, $url);

      //crawl HTML links
      while($child=array_shift($this->process)){
       if($child->url!=""){
         if(!in_array($child->url, ($this->crawled))){
           $this->crawl($child->url, ($child->level), $url);
         }
       }
     }
   }
  }

  private function inDomain($url) {
    $host = URL::extractHost($url);
    $domain = str_replace("www.", "", $this->domain);
    return (strpos($host,$domain)!==false);
  } 
};
?>
