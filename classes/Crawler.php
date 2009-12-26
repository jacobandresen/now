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
      $setting->name=$row[0];
      $setting->value=$row[1];
      array_push($this->filterSettings, $setting);
    }

    $this->httpClient = new HTTPClient($this->sDomain);
  }

  public function start()
  {
    mysql_query("delete from dump where account_id='".$this->iAccountId."'");
    $this->crawl( "http://".$this->sDomain, 0 , "http://".$this->sDomain);
  }

  public function crawl($sUrl, $iLevel, $sParent)
  {
    $document = $this->httpClient->getDocument($sUrl);
    $sResponse = $document->sContent;
    if($document->sContentType=="application/pdf"){
      $p=new PDFFilter($this->iAccountId);
      $sResponse = $p->filter($sUrl);
    }

    if(!($this->shouldWeCrawl($document))) {
      array_push( $this->aFound, $sUrl);
      array_push( $this->aCrawled, $sUrl);
      return false;
    }
    print "crawl [$iLevel] - $sUrl \r\n";

    preg_match_all("/(?:src|href)=\"([^\"]*?)\"/i", $sResponse, $aMatches);

    $sResponse = htmlentities($sResponse,ENT_QUOTES);
    if(!($this->add($sUrl, $document->sContentType, $sResponse, $iLevel))) return false;

    foreach($aMatches[1] as $sItem){
      $sFullUrl = URL::expandUrl($sItem, $sUrl);
      if ( (!in_array($sFullUrl, $this->aFound))
        and $this->URLFilter($sFullUrl)){
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
          array_push($this->aCrawled, $sChildUrl->sUrl);
          print "    connect ".$sChildUrl->sUrl."\r\n";
          $this->crawl($sChildUrl->sUrl, ($sChildUrl->iLevel), $sUrl);
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
    mysql_query("INSERT IGNORE into dump(account_id, url, contenttype,content, level) values('".$this->iAccountId."','$url','$contenttype', '$content', '$level')") or die (" failed to insert into dump:".mysql_error());
    return true;
  }

  private function shouldWeCrawl ($document)
  {
    if ($this->iLevel > $this->iMaxLevel){ return false;}
    if ($this->iCrawled>$this->iCrawlLimit){return false;}
    if (
      ($document->sContentType == "application/x-zip") ||
      ($document->sContentType == "application/xml") ||
      ($document->sContentType == "application/json") ||
      ($document->sContentType == "image/jpeg") ||
      ($document->sContentType == "image/jpg") ||
      ($document->sContentType == "image/gif") ||
      ($document->sContentType == "image/bmp") ||
      ($document->sContentType == "image/png") ||
      ($document->sContentType == "text/css") ||
      ($document->sContentType == "text/javascript")
    ){
      print "    skip - ".$document->sUrl." 'do not crawl ".$document->sContentType."'\r\n";
      return false;
    }
    return true;
  }

  private function URLFilter($sUrl)
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
