<?php

class Crawler {
  //admin settings 
  protected $sDomain;					  //string identifying the domain 
  protected $iMaxLevel;         //maximum distance from front page
  protected $iCrawlLimit;       //maximum number of urls to be crawled
 
  //user configurable settings 
  public  $filterSettings;      //regexes describing pages to be skipped

  //runtime information
  protected $iLevel;            //distance from front page
  protected $iCrawled;          //urls crawled so far
  protected $iSeen;             //urls seen so far during crawl
  protected $aFound;            //urls found so far
  protected $aCrawled;          //urls crawled so far 
  protected $aProcess;          //urls to be processed

  protected function setup($iAccountId) {
    $res = mysql_query('select level_limit, crawl_limit,domain from account where id="'.$iAccountId.'"');
    $row =  mysql_fetch_array($res);
    $this->iLevelLimitl = $row['level_limit'] ; 
    $this->iCrawlLimit = $row['crawl_limit'] ;
    $this->sDomain = $row['domain'] ; 

    $res = mysql_query('select name,value from setting where section="crawlerfilter" and account_id="'.$this->iAccountId.'";');
    $filters=array();
    while($row = mysql_fetch_array($res)) {
      $setting = new Setting();
      $setting->sName = $row[0];
      $setting->sValue = $row[1]; 
      array_push($filters , $setting);
    }
    $this->filterSettings=$filters; 
  }

  public function __construct($iAccountId){
    $this->iCrawled = 0;
    $this->iSeen=0 ;
    $this->aFound=array();
    $this->aCrawled=array();
    $this->aProcess=array();
    
    $this->setup($iAccountId);
  }

  public function reset () {
    mysql_query ("DELETE from dump where account_id='".$this->iAccountId."'") or die(mysql_error());
  }

  public function add ( $url, $html, $level ){
    print "  add [$level] - $url ".strlen($html)."\r\n";
    $url = utf8_decode($url); 
    $url = urlencode($url); 

    if(strlen($url)>256){
      print "URL too long \r\n";
      return;
    } 
   
    if(strlen($html)>4000000){
      print "FILE TOO BIG: $url \r\n"; 
      return; 
    } 
    $html = urlencode($html);
    
    mysql_query("INSERT IGNORE into dump(account_id, url, html, level) values('".$this->iAccountId."','$url', '$html', '$level')") or die (" failed to insert into dump:".mysql_error());
    return; 
  }

  public function start(){
    $this->crawl( "http://".$this->sDomain, 0 , "http://".$this->sDomain);
  }  

  public function crawl($sUrl, $iLevel, $sParent){
    print "crawl [$iLevel] - $sUrl \r\n";
    array_push( ($this->aCrawled), $sUrl);
    $this->iLevel=$iLevel; 
    if ($this->iLevel > $this->iMaxLevel){ return false;}
    if ($this->iCrawled>$this->iCrawlLimit){return false; } 

    //random wait (firewall buster)
    sleep(rand(0,3)); 	
	
    //grab contents of url
    preg_match("|\.pdf|i", $sUrl, $aMatch);
    if(count($aMatch)>0){
      $p=new PDFFilter();
      $sResponse = $p->filter($sUrl);
    }else{ 
      $sResponse= $this->getUrl($sUrl);
      $this->add($sUrl, $sResponse, $iLevel);
 
      preg_match_all("/(?:src|href)=\"([^\"]*?)\"/i", $sResponse, $aMatches);
      foreach($aMatches[1] as $sItem){
        $sFullUrl = $this->expandUrl($sItem, $sUrl);
        if (!in_array($sFullUrl, $this->aFound) 
          and $this->checkUrl($sFullUrl)){
            $oDoc = new Document();
            $oDoc->sUrl = $sFullUrl;
            $oDoc->iLevel = $iLevel+1;
            array_push($this->aFound, $oDoc);
            array_push($this->aProcess, $oDoc);
        }
      }
      $this->iCrawled++;

      //crawl links 
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
  
  public function getUrl ($sUrl) {
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
    $c->Close();
    return($sContent); 
  } 

  public function expandUrl($sItem, $sParent){
    $sPage="";	  
    if ($sItem == './'){
      $sItem = '/';
    }
    preg_match("@(http\s?\://[^\/].*?)(\/|$)@", $sParent, $aMatch);
    if ( count($aMatch) > 0 ){
      $sBase = $aMatch[1];
    }
    preg_match("@(http\s?\://[^\/].*?)\/([^\?]*?)(\?|$)@",$sParent, $aMatch);
    if ( count($aMatch) > 0 ){
      $sPage = $aMatch[2];
    }
    preg_match("|^http|", $sItem, $aMatch);
    if ( count($aMatch) > 0 ){
      return $sItem;
    }
    
    if($sPage){ 
      preg_match("|^\/$sPage|", $sItem, $aMatch);
      if ( count($aMatch) > 0 ){
        return $sBase.$sItem;
      }
      preg_match("|^$sPage|", $sItem, $aMatch);
      if ( count($aMatch) > 0 ){
        return $sBase.'/'.$sItem;
      }
    } 
   
    preg_match("|^\?|", $sItem, $aMatch);
    if ( count($aMatch) > 0 ){
      return $sBase.'/'.$sPage.$sItem;
    }
    $sUrl = $sBase.'/'.$sItem;
    return $sUrl;
  }

  public function checkUrl($sUrl){
    preg_match("|\@|",$sUrl, $aMatch);
    if ( count($aMatch) > 0 ){
      return false;
    }
    foreach( $this->filterSettings as $setting){
      $oItem = $setting->sValue; 
      preg_match("|$oItem|", $sUrl, $aMatch);
      if( count($aMatch) > 0){
        return false; 
      } 
    }
    preg_match("|".$this->sDomain."|", $sUrl, $aMatch);
    if (count($aMatch) > 0) {
        return true; 
     }
    return false;
  }
};

?>
