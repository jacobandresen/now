<?php

require_once('PDFFilter.php');
require_once('Document.php');
require_once('Setting.php');

class Crawler {

  protected $iAccountId;        //customer number in database

  protected $iMaxLevel;         //maximum distance from front page
  protected $iCrawlLimit;       //maximum number of urls to be crawled

  //configurable settings
  public $filterSettings;       //regexes to be skipped

  //runtime information
  protected $iLevel;            //distance from front page
  protected $iCrawled;          //urls crawled so far
  protected $iSeen;             //urls seen so far during crawl
  public    $aFound;            //urls found so far
  protected $aCrawled;          //urls crawled so far 
  protected $aProcess;          //urls to be processed

  //domains configured for account
  public  $aDomains;

  public function __construct($iAccountId){
    $this->iAccountId = $iAccountId;
    $this->iCrawled = 0;
    $this->iSeen=0 ;
    $this->aFound=array();
    $this->aCrawled=array();
    $this->aProcess=array();
 
    //hardcoded settings 
    $this->iMaxLevel=40;
    $this->iCrawlLimit = 5000;

    //domains to be crawled 
    $aDomains = array(); 
    $res = mysql_query("SELECT * from domain where account_id='".$iAccountId."'");
    while ($row =mysql_fetch_array($res) ) {
       $sName = $row['name'];
       array_push( $aDomains, $sName ) ;
    }
    $this->aDomains = $aDomains; 
    $this->filterSettings = new Setting(§iAccountId, "filters");
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
   $html = utf8_decode($html); 
   $html = urlencode($html);
    
   mysql_query("INSERT IGNORE into dump(account_id, url, html, level) values('".$this->iAccountId."','$url', '$html', '$level')") or die (" failed to insert into dump:".mysql_error());
   
   return; 
 }

 public function getUrl ($sUrl) {
   $c=new HttpClient();
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

 public function crawler($sUrl, $iLevel, $sParent){
   print "crawler [$iLevel] - $sUrl \r\n";
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
     $sReponse = $p->filter($sUrl);
   }else{ 
     $sResponse= $this->getUrl($sUrl);
    
     $this->add($sUrl, $sResponse, $iLevel);
     //get links from url 
     preg_match_all("/(?:src|href)=\"([^\"]*?)\"/i", $sResponse, $aMatches);

    foreach($aMatches[1] as $sItem){
      $sFullUrl = $this->expandUrl($sItem, $sUrl);
    if (!in_array($sFullUrl, $this->aFound) and $this->bValidUrl($sFullUrl)){
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
          $this->crawler($sChildUrl->sUrl, ($sChildUrl->iLevel), $sUrl);
         }   
      } 
    }
   } 
  }
  
  public function crawl($sUrl, $iLevel, $sParent) {
    print "crawl [$iLevel] - $sUrl \r\n";
    array_push( ($this->aCrawled), $sUrl);
    $this->iLevel=$iLevel; 
    if ($this->iLevel > $this->iMaxLevel){ return false;}
    if ($this->iCrawled>$this->iCrawlLimit){return false; } 

   //random wait (firewall buster)
   //sleep(rand(0,3)); 	
   
    //grab contents of url
    $sResponse= $this->getUrl($sUrl);
    $this->add($sUrl, $sResponse, $iLevel);
    //get links from url 
    preg_match_all("/(?:src|href)=\"([^\"]*?)\"/i", $sResponse, $aMatches);
    
   foreach($aMatches[1] as $sItem){
      $sFullUrl = $this->expandUrl($sItem, $sUrl);
      if (!in_array($sFullUrl, $this->aFound) and $this->bValidUrl($sFullUrl)){
        $oDoc = new Document();
        $oDoc->sUrl = $sFullUrl;
        $oDoc->iLevel = $iLevel+1;
        array_push($this->aFound, $oDoc);
        array_push($this->aProcess, $oDoc);
        //$stamp=time();
        #$sContent=$this->getUrl($sFullUrl); 
        #$this->add($sFullUrl, $sContent, $iLevel);
      }
    }
    $this->iCrawled++;
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

  /**
   *Make sure that we want to crawl the url
   */ 
  public function bValidUrl($sUrl){
    preg_match("|\@|",$sUrl, $aMatch);
    if ( count($aMatch) > 0 ){
      return false;
    }

    foreach( $this->filterSettings->getAll() as $oField){
      $oItem=$oField->value;
      preg_match("|$oItem|", $sUrl, $aMatch);
      if( count($aMatch) > 0){
        return false; 
      } 
    }

    foreach( $this->aDomains as $oItem){
      preg_match("|$oItem|", $sUrl, $aMatch);
      if( count($aMatch) > 0){
        return true; 
      } 
     }
   
    return false;
  }
};

?>
