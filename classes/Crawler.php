<?php
class Crawler
{
  private $sDomain;
  private $iMaxLevel;
  private $iCrawlLimit;

  private $filterSettings;

  private $iLevel;
  private $iCrawled;
  private $iSeen;
  private $aFound;
  private $aCrawled;
  private $aProcess;

  private $sLastContentType;

  public function __construct($iAccountId)
  {
    $this->iCrawled = 0;
    $this->iSeen=0 ;
    $this->aFound=array();
    $this->aCrawled=array();
    $this->aProcess=array();
    $this->iAccountId=$iAccountId;

    $res = mysql_query('select level_limit, crawl_limit,domain from account where id="'.$iAccountId.'"');
    $row =  mysql_fetch_array($res);
    $this->iMaxLevel = $row['level_limit'] ;
    $this->iCrawlLimit = $row['crawl_limit'] ;
    $this->sDomain = $row['domain'];

    if ($this->sDomain==""){
       throw new Exception("missing domain");
    }

    $this->filterSettings=Setting::mkSettings("crawlerfilter", $iAccountId);
  }

  public function crawl($sUrl, $iLevel, $sParent)
  {
    print "crawl [$iLevel] - $sUrl \r\n";
    array_push( ($this->aCrawled), $sUrl);

    $this->iLevel=$iLevel;
    if ($this->iLevel > $this->iMaxLevel){ return false;}
    if ($this->iCrawled>$this->iCrawlLimit){return false;}

    $sResponse= $this->getUrl($sUrl);
    $this->sLastContentType=trim($this->sLastContentType);
    print "content-type:[".$this->sLastContentType."]\r\n";

    if (
      (trim($this->sLastContentType) == "application/x-zip") ||
      (trim($this->sLastContentType) == "image/jpeg")
    ){
      return;
    }

    if((trim($this->sLastContentType))=="application/pdf"){
      print "found pdf\r\n";
      $p=new PDFFilter($this->iAccountId);
      $sResponse = $p->filter($sUrl);
    }else{

      //TODO: we should also add pdfs!
      $this->add($sUrl, $sResponse, $iLevel);

      preg_match_all("/(?:src|href)=\"([^\"]*?)\"/i", $sResponse, $aMatches);
      foreach($aMatches[1] as $sItem){
        $sFullUrl = URL::expandUrl($sItem, $sUrl);
        if ( (!in_array($sFullUrl, $this->aFound)) and $this->checkUrl($sFullUrl)){
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
    $html = urlencode($html);
    mysql_query("INSERT IGNORE into dump(account_id, url, html, level) values('".$this->iAccountId."','$url', '$html', '$level')") or die (" failed to insert into dump:".mysql_error());
    return;
  }

  public function start()
  {
    //$this->reset();
    $this->crawl( "http://".$this->sDomain, 0 , "http://".$this->sDomain);
  }


  //TODO: refactor this to HTTPClient
  public function getUrl ($sUrl)
  {
    $c=new HTTPClient();
    $sHost = $c->extractHost($sUrl);
    if($sHost!=""){
      $c->connect($sHost);
    }
    $sContent = $c->get($sUrl);
    if (isset($c->sFinalUrl) && $sUrl!=$c->sFinalUrl){
      print $sUrl ." -> ".$c->sFinalUrl."\r\n";
      array_push($this->aCrawled, $sFinalUrl);
    }
    $this->sLastContentType=$c->sContentType;
    $c->Close();
    return($sContent);
  }


  public function checkUrl($sUrl)
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
