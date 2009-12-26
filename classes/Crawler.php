<?php
class Crawler
{
  private $accountId;
  private $domain;
  private $maxLevel;
  private $crawlLimit;

  private $level;
  private $numberCrawled;
  private $found;
  private $crawled;
  private $process;

  private $filterSettings;

  private $httpClient;

  public function __construct($accountId)
  {
    $this->numberCrawled = 0;
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
    $document = $this->httpClient->getDocument($url);
    $response = $document->content;
    if($document->contentType=="application/pdf"){
      $p=new PDFFilter($this->accountId);
      $response = $p->filter($url);
    }

    if(!($this->shouldWeCrawl($document))) {
      array_push( $this->found, $url);
      array_push( $this->crawled, $url);
      return false;
    }
    print "crawl [$level] - $url \r\n";

    preg_match_all("/(?:src|href)=\"([^\"]*?)\"/i", $response, $matches);

    $response = htmlentities($response,ENT_QUOTES);
    if(!($this->add($url, $document->contentType, $response, $level))) return false;
    unset($response);

    foreach($matches[1] as $item){
      $fullUrl = URL::expandUrl($item, $url);
      if ( (!in_array($fullUrl, $this->found))
        and $this->URLFilter($fullUrl)){
        $document = new Document();
        $document->url = $fullUrl;
        $document->level = $level+1;
        array_push($this->found, $document);
        array_push($this->process, $document);
      }
    }
    $this->numberCrawled++;

    while($childUrl=array_shift($this->process)){
      if($childUrl->url!=""){
        if(!in_array($childUrl->url, ($this->crawled))){
          array_push($this->crawled, $childUrl->url);
          print "    connect ".$childUrl->url."\r\n";
          $this->crawl($childUrl->url, ($childUrl->level), $url);
        }
      }
    }
  }

  public function add ( $url, $contenttype,$content, $level )
  {
    $url = utf8_decode($url);

    if(strlen($url)>1028){
      print "    skip  - $url 'URL too long'\r\n";
      return false;
    }
    if(strlen($content)>MAX_CONTENT_LENGTH){
      print "    skip - $url 'content too big' \r\n";
      return false;
    }
    if(strlen($content)<1){
      print "    skip - $url 'no content' \r\n";
      return false;
    }
    print "  add [$level] - ".$url." ".strlen($content)." ".MAX_CONTENT_LENGTH."\r\n";
    $url = urlencode($url);
    mysql_query("INSERT IGNORE into dump(account_id, url, contenttype,content, level) values('".$this->accountId."','$url','$contenttype', '$content', '$level')") or die (" failed to insert into dump:".mysql_error());
    return true;
  }

  private function shouldWeCrawl ($document)
  {
    if ($this->level > $this->maxLevel){ return false;}
    if ($this->numberCrawled>$this->crawlLimit){return false;}
    if (
      ($document->contentType == "application/x-zip") ||
      ($document->contentType == "application/xml") ||
      ($document->contentType == "application/json") ||
      ($document->contentType == "image/jpeg") ||
      ($document->contentType == "image/jpg") ||
      ($document->contentType == "image/gif") ||
      ($document->contentType == "image/bmp") ||
      ($document->contentType == "image/png") ||
      ($document->contentType == "text/css") ||
      ($document->contentType == "text/javascript")
    ){
      print "    skip - ".$document->url." 'do not crawl ".$document->contentType."'\r\n";
      return false;
    }
    return true;
  }

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
