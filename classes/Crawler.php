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

  private $filterSettings;

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

    $res = mysql_query('select name,value from crawlerfilter  where account_id="'.$accountId.'"');
    $this->filterSettings =array();
    while( $row =  mysql_fetch_array($res) ) {
      $setting=new Setting();
      $setting->name=$row[0];
      $setting->value=$row[1];
      array_push($this->filterSettings, $setting);
    }

    $this->httpClient = new HTTPClient($this->domain);
  }

  public function start()
  {
    mysql_query("delete from dump where account_id='".$this->accountId."'");
    $this->crawl( "http://".$this->domain, 0 , "http://".$this->domain);
  }

  public function crawl($url, $level, $parent)
  {
    print "crawl [$level] - $url \r\n";

    $document = $this->httpClient->getDocument($url);
    $document->level = $level;

    if ($document->contentType=="application/pdf") {
      $p=new PDFFilter($this->accountId);
      $document->content = $p->filter($document);
    } else {

      //default to html
      if ($this->level > $this->maxLevel){ return false;}
      if (count($this->crawled)>$this->crawlLimit){return false;}
      if (!$document->shouldCrawl()) {
        array_push( $this->crawled, $url);
        return false;
      }

      //get html links 
      preg_match_all("/(?:src|href)=\"([^\"]*?)\"/i", 
                     $document->content, $matches);
      foreach ($matches[1] as $item) {
        $fullUrl = URL::expandUrl($item, $url);
        if ( (!in_array($fullUrl, $this->found) &&
          $this->URLFilter($fullUrl))){
          $link = new Document();
          $link->url = $fullUrl;
          $link->level = $level+1;
          array_push($this->found, $link);
          array_push($this->process, $link);
        }
      }

      //crawl links
      while($child=array_shift($this->process)){
       if($child->url!=""){
         if(!in_array($child->url, ($this->crawled))){
           array_push($this->crawled, $child->url);
           $this->crawl($child->url, ($child->level), $url);
         }
       }
     }

    //prepare for storage in database
    $document->content = htmlentities($document->content,ENT_QUOTES);
   }
   
   return $document->save($this->accountId);
  }

  //TODO: refactor to URL class
  private function URLFilter($url)
  {
    preg_match("|\@|",$url, $match);
    if ( count($match) > 0 ){
      array_push($this->crawled, $url);
      print "    skip ".$url. " - is an email \r\n";
      return false;
    }

    if(strpos($url, "javascript:")){
       print "    skip ".$url. " - is a javascript link \r\n";
       return false;
    }

    foreach( $this->filterSettings as $setting){
      $item = urldecode($setting->value);
      if ($item!=""){
        preg_match("|$item|", $url, $match);
        if( count($match) > 0){
          array_push($this->crawled, $url);
          print "\t".$url." - failed on filter $item \r\n";
          return false;
        }
      }
    }
    preg_match("|".$this->domain."|", $url, $match);
    if (count($match) > 0) {
      return true;
    }
    array_push($this->crawled, $url);
    return false;
  }
};
?>
