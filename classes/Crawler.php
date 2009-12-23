<?php
class Crawler
{
  private $iAccountId;
  private $sDomain;
  private $iMaxLevel;
  private $iCrawlLimit;

  private $iLevel;
  private $iCrawled;
  private $aFound;
  private $aCrawled;
  private $aProcess;

  private $filterSettings;

  private $httpClient;

  public function __construct($iAccountId)
  {
    $this->iCrawled = 0;
    $this->aFound=array();
    $this->aCrawled=array();
    $this->aProcess=array();

    $this->iAccountId=$iAccountId;
    $res = mysql_query('select level_limit,crawl_limit,domain from account where id="'.$iAccountId.'"');
    $row =  mysql_fetch_array($res);
    $this->iMaxLevel = $row['level_limit'] ;
    $this->iCrawlLimit = $row['crawl_limit'] ;
    $this->sDomain = $row['domain'];


    $res = mysql_query('select name,value from crawlerfilter  where account_id="'.$iAccountId.'"');
    $this->filterSettings =array();
    while( $row =  mysql_fetch_array($res) ) {
      $setting=new Setting();
      array_push($this->filterSettings, $setting);
    }

    $this->httpClient = new HTTPClient($this->sDomain);
  }

  public function start()
  {
    $this->crawl( "http://".$this->sDomain, 0 , "http://".$this->sDomain);
  }

  public function crawl($sUrl, $iLevel, $sParent)
  {
    print "crawl [$iLevel] - $sUrl \r\n";
    array_push( ($this->aCrawled), $sUrl);

    $document = $this->httpClient->getDocument($sUrl);

    if($this->shouldWeCrawl($document))
    {
      $sResponse = $document->sContent;

      if($document->sContentType=="application/pdf"){
        print "found pdf\r\n";
        $p=new PDFFilter($this->iAccountId);
        $sResponse = $p->filter($sUrl);
      }

      $this->add($sUrl, $sResponse, $iLevel);

      preg_match_all("/(?:src|href)=\"([^\"]*?)\"/i", $sResponse, $aMatches);
      foreach($aMatches[1] as $sItem){
        $sFullUrl = URL::expandUrl($sItem, $sUrl);
        if ( (!in_array($sFullUrl, $this->aFound))
          and $this->filter($sFullUrl)){
          $oDoc = new Document();
          $oDoc->sUrl = $sFullUrl;
          $oDoc->iLevel = $iLevel+1;
          array_push($this->aFound, $oDoc);
          array_push($this->aProcess, $oDoc);
        }
      }

      $this->iCrawled++;

      while($sChildUrl=array_shift($this->aProcess)){
        if($sChildUrl->sUrl!=""){
          if(!in_array($sChildUrl->sUrl, ($this->aCrawled))){
            print "connect [$sChildUrl->iLevel] $sUrl -> $sChildUrl->sUrl \r\n";
            array_push($this->aCrawled, $sChildUrl->sUrl);
            $this->crawl($sChildUrl->sUrl, ($sChildUrl->iLevel), $sUrl);
          }
        }
      }
    }
  }

  public function add ( $url, $html, $level )
  {
    print "  add [$level] - $url ".strlen($html)."\r\n";
    $url = utf8_decode($url);
    $url = urlencode($url);

    if(strlen($url)>1028){
      print "URL too long \r\n";
      return;
    }
    if (strlen($html)<1000000) {
      print " data:".strlen($html)."\r\n";
      $html = urlencode($html);
      mysql_query("INSERT IGNORE into dump(account_id, url, html, level) values('".$this->iAccountId."','$url', '$html', '$level')") or die (" failed to insert into dump:".mysql_error());
    }else{
       print "[$url] : content too big \r\n";
    }
    return;
  }

  private function shouldWeCrawl ($document)
  {
    print "url:[".$document->sUrl."]\r\n";
    print "content type:[".$document->sContentType."]\r\n";
    if ($this->iLevel > $this->iMaxLevel){ return false;}
    if ($this->iCrawled>$this->iCrawlLimit){return false;}
    if (
      ($document->sContentType == "application/x-zip") ||
      ($document->sContentType == "application/xml") ||
      ($document->sContentType == "image/jpeg") ||
      ($document->sContentType == "image/jpg") ||
      ($document->sContentType == "image/gif") ||
      ($document->sContentType == "image/bmp") ||
      ($document->sContentType == "image/png") ||
      ($document->sContentType == "text/css") ||
      ($document->sContentType == "text/xml")
    ){
      print "CONTENT TYPE FAIL:".$document->sContentType."\r\n";
      return false;
    }
    return true;
  }


  private function filter($sUrl)
  {
    preg_match("|\@|",$sUrl, $aMatch);
    if ( count($aMatch) > 0 ){
      array_push($this->aCrawled, $sUrl);
      print "\t".$sUrl. " - is an email \r\n";
      return false;
    }

    foreach( $this->filterSettings as $setting){
      $oItem = urldecode($setting->sValue);
      if ($oItem!=""){
        preg_match("|$oItem|", $sUrl, $aMatch);
        if( count($aMatch) > 0){
          array_push($this->aCrawled, $sUrl);
          print "\t".$sUrl." - failed on filter $oItem \r\n";
          return false;
        }
      }
    }
    preg_match("|".$this->sDomain."|", $sUrl, $aMatch);
    if (count($aMatch) > 0) {
      return true;
    }
    array_push($this->aCrawled, $sUrl);
    return false;
  }
};
?>
