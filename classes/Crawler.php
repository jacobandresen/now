<?php

class Crawler {
       
 public    $aFound;             //urls found so far
 protected $iLevel;             //distance from front page
 protected $iCrawled;           //urls crawled so far
 protected $iSeen;              //urls seen so far during crawl
 protected $aCrawled;           //urls crawled so far 
 protected $aProcess;           //urls to be processed
 protected $iCustomerId;        //customer number in database
 protected $iMaxLevel;          //maximum distance from front page
 protected $iCrawlLimit;        //maximum number of urls to be crawled

 public   $aFilterAdd;		//domains to be crawled
 public   $aFilterSkip;	        //skip filters for urls


 public function __construct($iCustomerId){
	 
    $this->iCustomerId = $iCustomerId;
    $this->iCrawled = 0;
    $this->iSeen=0 ;

    //crawl settings 
    $res = mysql_query("SELECT * from user where id='".$this->iCustomerId."'") or die(mysql_error());
    if ($row=mysql_fetch_array($res)){
      if ($row['level_limit']>0) { 
       $this->iMaxLevel=$row['level_limit'];
       }else{
       $this->iMaxLevel=20;
      }
      if ($row['crawl_limit']>0){
       $this->iCrawlLimit=$row['crawl_limit'];
      }else{
       $this->iCrawlLimit=500; 
      }
    }else{
      $this->iMaxLevel=20;     
      $this->iCrawlLimit=500;   
    }

    //fetch domains to be crawled
    $u=new UserManagement();
    $aDomain= $u->getDomains($iCustomerId);
    $aFilterAdd = array(); 
    for ($i=0;$i<sizeof($aDomain);$i++){
     array_push( $aFilterAdd, $aDomain[$i]);
    }
    $this->aFilterAdd = $aFilterAdd;
    $this->aFound=array();
    $this->aCrawled=array();
    $this->aProcess=array();
  }

  public function clear () {
    mysql_query ("DELETE from dump where user_id='".$this->iCustomerId."'") or die(mysql_error());
  }

  public function add ( $url, $html, $level ){
   print "  add [$level] - $url ".strlen($html)."\r\n";
   //avoid sql injection attacks 
   $url = urlencode($url); 

   if(strlen($url)>256){
    print "URL too long \r\n";
    return;
   } 
   
   if(strlen($html)>200000){
     print "FILE TOO BIG\r\n"; 
     return; 
   } 
   $html = urlencode($html);
   mysql_query("INSERT IGNORE into dump(user_id, url, html, level) values('".$this->iCustomerId."','$url', '$html', '$level')") or die (" failed to insert into dump:".mysql_error());
   return; 
  }

  public function getUrl ($sUrl) {
     $c=new HttpClient();
     $sHost = $c->extractHost($sUrl);
     if($sHost!=""){
       $c->connect($sHost);
     }
   
   $sContent = $c->get($sUrl);
   if ($sUrl!=$c->sFinalUrl){
      print $sUrl ." -> ".$c->sFinalUrl."\r\n"; 
      array_push($this->aCrawled, $sFinalUrl); 
   } 

   $c->Close();
   return($sContent); 
   } 

  public function crawler($sUrl, $iLevel, $sParent){
    print "crawl [$iLevel] - $sUrl \r\n";
    array_push( ($this->aCrawled), $sUrl);
    $this->iLevel=$iLevel; 
    if ($this->iLevel > $this->iMaxLevel){ return false;}
    if ($this->iCrawled>$this->iCrawlLimit){return false; } 

    //grab contents of url
    $sResponse= $this->getUrl($sUrl);
    $this->add($sUrl, $sResponse, $iLevel);
    //get links from url 
    preg_match_all("|href=\"([^\"]*?)\"|i", $sResponse, $aMatches);
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
    //print 'Crawled: '.$this->iCrawled."\r\n";

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
  
  public function crawl($sUrl, $iLevel, $sParent) {
    print "crawl [$iLevel] - $sUrl \r\n";
    array_push( ($this->aCrawled), $sUrl);
    $this->iLevel=$iLevel; 
    if ($this->iLevel > $this->iMaxLevel){ return false;}
    if ($this->iCrawled>$this->iCrawlLimit){return false; } 

    //grab contents of url
    $sResponse= $this->getUrl($sUrl);
    $this->add($sUrl, $sResponse, $iLevel);
    //get links from url 
    preg_match_all("|href=\"([^\"]*?)\"|i", $sResponse, $aMatches);
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

  //Make sure that we want to crawl the url
  public function bValidUrl($sUrl){
    preg_match("|\@|",$sUrl, $aMatch);
    if ( count($aMatch) > 0 ){
      return false;
    }

    foreach ($this->aFilterSkip as $oItem){
      preg_match("|$oItem|", $sUrl, $aMatch);
      if( count($aMatch) > 0){
          print "SKIP $sUrl \r\n"; 
          return false;
      }
     } 

    foreach ($this->aFilterAdd as $oItem){
      preg_match("|$oItem|",$sUrl, $aMatch);
      if ( count($aMatch) > 0 ){
     	return true;
      }
    }
    return false;
  }
};

class Document{
  public $sUrl = "";
  public $sContent = "";
  public $iLevel = 0;
};
?>
