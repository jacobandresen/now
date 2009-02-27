<?php
//2009, Jacob Andresen <jacob.andresen@gmail.com>
//2009, Johan Bächström <johbac@gmail.colm>
class Crawler {
       
 protected $iMaxFileSize        = 167000;
 protected $pdftmp              = "/tmp";
 
 public    $aFound;             //urls found so far
 protected $iLevel;             //distance from front page
 protected $iCrawled;           //urls crawled so far
 protected $iSeen;              //urls seen so far during crawl
 protected $aCrawled;           //urls crawled so far 
 protected $aProcess;           //urls to be processed
 protected $iCustomerId;        //customer number in database
 protected $iMaxLevel;          //maximum distance from front page
 protected $iCrawlLimit;        //maximum number of urls to be crawled
 protected $aFilterAdd;         //which domains to be crawled
 protected $aFilterSkip;        //url patterns to be skipped in crawl

 public function getSkipFilters() {
   return($this->updateSkipFilters());
 }

 public function updateSkipFilters() {
    $res = mysql_query("select filter from crawlskip where user_id='".$this->iCustomerId."'");
    $this->aFilterSkip=array();   
    while ($row = mysql_fetch_array($res) ){
      array_push($this->aFilterSkip, $row['filter']);
    }
   return $this->aFilterSkip;
 }

 public function addSkipFilter( $filter ) { 
   mysql_query("INSERT into crawlskip(user_id,filter) values('".$this->iCustomerId."','".$filter."')") ; 
   $this->updateSkipFilters(); 
 } 
 
 public function delSkipFilter ( $filter ){
   mysql_query("DELETE from crawlskip where user_id='".$this->iCustomerId."' and filter='".$filter."'") or die(mysql_error()); 
 }

 public function delSkipFilters ( ){
   mysql_query("DELETE from crawlskip where user_id='".$this->iCustomerId."'") or die(mysql_error());
 }

 public function __construct($iCustomerId){
    if ((!(isset($iCustomerId))) ||  $iCustomerId<0){
      die("crawl:invalid customer id \r\n"); 
     }
    $this->iCustomerId = $iCustomerId;
    //crawl state
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

    $this->updateSkipFilters();

    $this->aFound=array();
    $this->aCrawled=array();
    $this->aProcess=array();
  }

  public function addFilter($sFilter){
    $this->aFilterAdd=array($sFilter);
  }

  public function clear () {
    mysql_query ("DELETE from dump where user_id='".$this->iCustomerId."'") or die(mysql_error());
    $this->delSkipFilters();  
  }

  public function pdftotext($content){
    $myFile = "/tmp/pdftmp";
    $fh = fopen($myFile, 'w') or die("can't open file");
    fwrite($fh, $content);
    fclose($fh);
    exec("pdftotext /tmp/pdftmp");// or die("cannot execute pdftotext");  
    $fh = fopen($myFiler, 'r') or die("can't open file");
    $txt = fread($fh, filesize($myFile));
    fclose($fh);
    return($txt);
  }

  public function add ( $url, $html, $level ){
   print "  add [$level] - $url \r\n";
 //  if (preg_match("/\.pdf/i", $url)){
 //    $html=$this->pdftotext($html);  
 //  }

   //avoid sql injection attacks 
   $url = urlencode($url); 
   
   if(sizeof($html)>20000000){
     print "FILE TOO BIG\r\n"; 
     return; 
   } 
   $html = urlencode($html);
 
   if (strlen($html) < $this->iMaxFileSize) {
    mysql_query("INSERT into dump(user_id, url, html) values('".$this->iCustomerId."','$url', '$html')") or die (mysql_error());
   } 
   return; 
  }

  public function getUrl ($sUrl) {
     $c=new HttpClient();
     $sHost = $c->extractHost($sUrl);
     if($sHost!=""){
       $c->connect($sHost);
     }
     return($c->get($sUrl)); 
   } 

  public function bCrawl($sUrl, $iLevel, $sParent) {
    print "crawl [$iLevel] - $sUrl \r\n";    
    array_push( ($this->aCrawled), $sUrl); 
    $this->iLevel=$iLevel; 
    if ($this->iLevel > $this->iMaxLevel){ return false;}
    if ($this->iCrawled>$this->iCrawlLimit){return false; } 

    //grab contents of url
    $sResponse= $this->getUrl($sUrl);
   
    //get links from url 
    preg_match_all("|href=\"([^\"]*?)\"|i", $sResponse, $aMatches);
    foreach($aMatches[1] as $sItem){
      $sFullUrl = $this->sGetFullUrl($sItem, $sUrl);
      if (!in_array($sFullUrl, $this->aFound) and $this->bValidUrl($sFullUrl)){
        array_push($this->aFound, $sFullUrl);
        array_push($this->aProcess, $sFullUrl);
        //$stamp=time();
        $sContent=$this->getUrl($sFullUrl); 
        $this->add($sFullUrl, $sContent, $iLevel);
      }
    }
    $this->iCrawled++;

    //crawl links 
    while($sChildUrl=array_shift($this->aProcess)){ 
     if($sChildUrl!=""){ 
        if(!in_array($sChildUrl, ($this->aCrawled))){  
          print "connect [$iLevel] $sUrl -> $sChildUrl \r\n"; 
          array_push($this->aCrawled, $sChildUrl); 
          $this->bCrawl($sChildUrl, ($iLevel+1), $sUrl);
        }   
      } 
    }
  }

  public function sGetFullUrl($sItem, $sParent){
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
    preg_match("|^\/$sPage|", $sItem, $aMatch);
    if ( count($aMatch) > 0 ){
      return $sBase.$sItem;
    }
    preg_match("|^$sPage|", $sItem, $aMatch);
    if ( count($aMatch) > 0 ){
      return $sBase.'/'.$sItem;
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
    foreach ($this->aFilterSkip as $oItem){
      preg_match("|$oItem|",$sUrl, $aMatch);
      if ( count($aMatch) > 0 ){
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
?>
