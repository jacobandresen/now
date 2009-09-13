<?php

class Crawler 
{
    protected $sDomain;		  //string identifying the domain 
    protected $iMaxLevel;         //maximum distance from front page
    protected $iCrawlLimit;       //maximum number of urls to be crawled
    protected $filterSettings;    //regexes describing pages to be skipped
    protected $iLevel;            //distance from front page
    protected $iCrawled;          //urls crawled so far
    protected $iSeen;             //urls seen so far during crawl
    protected $aFound;            //urls found so far
    protected $aCrawled;          //urls crawled so far 
    protected $aProcess;          //urls to be processed
    protected $sLastContentType;  //last content type seen by crawler

    
    protected function setup($iAccountId) 
    {
        $res = mysql_query('select level_limit, crawl_limit,domain from account where id="'.$iAccountId.'"');
        $row =  mysql_fetch_array($res);
        $this->iMaxLevel = $row['level_limit'] ; 
        $this->iCrawlLimit = $row['crawl_limit'] ;
        $this->sDomain = $row['domain'] ;  //note: one domain pr acocunt
    
        $this->filterSettings=Setting::mkSettings("crawlerfilter", $iAccountId);
    }

    public function __construct($iAccountId)
    {
        $this->iCrawled = 0;
        $this->iSeen=0 ;
        $this->aFound=array();
        $this->aCrawled=array();
        $this->aProcess=array();
        $this->iAccountId=$iAccountId; 
        $this->setup($iAccountId);
    }

    public function reset () 
    {
        mysql_query ("DELETE from dump where account_id='".$this->iAccountId."'") or die(mysql_error());
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
        $this->reset();
        $this->crawl( "http://".$this->sDomain, 0 , "http://".$this->sDomain);
    }  

    
    public function crawl($sUrl, $iLevel, $sParent)
    {
        print "crawl [$iLevel] - $sUrl \r\n";
        array_push( ($this->aCrawled), $sUrl);

        $this->iLevel=$iLevel; 
        if ($this->iLevel > $this->iMaxLevel){ return false;}
        if ($this->iCrawled>$this->iCrawlLimit){return false; } 

        //random wait (firewall buster)
        //sleep(rand(0,3)); 	
    
        //grab contents of url and find content type
        $sResponse= $this->getUrl($sUrl);
        print "content-type:[".$this->sLastContentType."]\r\n"; 
    
        //preg_match("|\.pdf|i", $sUrl, $aMatch);
        //if(count($aMatch)>0 || $this->sLastContentType=="application/pdf"){
        if((trim($this->sLastContentType))=="application/pdf"){
            print "found pdf\r\n"; 
            $p=new PDFFilter($this->iAccountId);
            $sResponse = $p->filter($sUrl);
        }else{ 
            $this->add($sUrl, $sResponse, $iLevel);
 
            preg_match_all("/(?:src|href)=\"([^\"]*?)\"/i", $sResponse, $aMatches);
            foreach($aMatches[1] as $sItem){
                $sFullUrl = $this->expandUrl($sItem, $sUrl);
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

    public function expandUrl($sItem, $sParent)
    {
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
            if ($oItem!=""){  //do not attempt negative match on empty string
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
