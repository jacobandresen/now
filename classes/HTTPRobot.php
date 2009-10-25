<?php
require_once("Structures.php");
require_once("Administration.php");
require_once("HTTPClient.php");
require_once("Filter.php");

class HTTPRobot
{
  public function run($iAccountId) {
    $c=new Crawler($iAccountId);
    $c->run();

    $indx=new Indexer($iAccountId);
    $indx->clear();
    $indx->index();
  }
}


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
    print "content-type:[".$this->sLastContentType."]\r\n";

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

class Indexer
{
  private $iAccountId;
  private $filterSettings;
  private $bodyFilter;

  public function __construct($iAccountId)
  {
    $this->iAccountId=$iAccountId;
    $this->sBodyFilter="";
    $this->filterSettings=Setting::mkSettings("indexerfilter", $this->iAccountId);
  }
  public function reset()
  {
    $sSQL = "DELETE from document where account_id='".$this->iAccountId."'";
    mysql_query( $sSQL ) or die(mysql_error());
  }


  public function clear()
  {
    mysql_query("DELETE FROM document where account_id='".$this->iAccountId."'") or die (mysql_error());
  }

  public function start()
  {
    $this->clear();
    $this->index();
  }

  public function index()
  {
    print "start INDEX\r\n";
    $sSQL="select max(retrieved),url,html,level from dump where account_id='".$this->iAccountId."' group by account_id,url";
    $res = mysql_query($sSQL) or die (mysql_error());

    while($row=mysql_fetch_array($res)){
      try{
        $this->add(urldecode($row['url']),
          urldecode($row['html']),
          $row['level']);
      }catch(Exception $e){
        print "FAILED: $url \r\n";
      }
    }
  }

  public function add($url, $body, $level)
  {
    print "ADD: $url \r\n";
    try{
      $title="";
      $url= urlencode($url);

      if($this->filterSettingsp) {
        foreach ($this->filterSettingsp as $setting){
          $oItem = urldecode($setting->sValue);
          if ($oItem!="") {
            preg_match("|$oItem|", $url, $aMatch);
            if ( count($aMatch) > 0){
              print "SKIP DUE TO :".$oItem."\r\n";
              return false;
            }
          }
        }
      }

      $res= mysql_query("SELECT url from document where url='$url' and account_id='".$this->iAccountId."'") or die(mysql_error());
      if($row=mysql_fetch_array($res)){
        print "duplicate: $url <br>  -> ".$row['url']."\r\n";
        return false;
      }
      //process content
      $orig=$body;

      if ($this->isUTF8($body)){
        $body = iconv("UTF-8", "ISO-8859-1", $body);
      }

      $timestmp=time();
      $sFound='';

      //find title
      if ($title == ''){
        preg_match("|<.*?content_header[^>]*?\>(.*?)<\/[^>]*?\>|is", $body, $aMatches);
        if(sizeof($aMatches)){
          $title = $aMatches[1];
          $sFound = 'h1';
        }
      }
      if ($title == ''){
        preg_match("|<h1>(.*?)<\/h1>|is", $body, $aMatches);
        if(sizeof($aMatches)){
          $title = $aMatches[1];
          $sFound = 'h1';
        }
      }
      if ($title == ''){
        preg_match("|<h2>(.*?)<\/h2>|is", $body, $aMatches);
        if(sizeof($aMatches)){
          $title = $aMatches[1];
          $sFound = 'h2';
        }
      }
      if ($title == ''){
        preg_match("|<title>(.*?)<\/title>|is", $body, $aMatches);
        if(sizeof($aMatches)){
          $title = $aMatches[1];
          $sFound = 'title';
        }
      }
      if ($title == ''){
        preg_match("|<h3>(.*?)<\/h3>|is", $body, $aMatches);
        if(sizeof($aMatches)){
          $title = $aMatches[1];
          $sFound = 'h3';
        }
      }
      if ($title == ''){
        preg_match("|<h4>(.*?)<\/h4>|is", $body, $aMatches);
        if(sizeof($aMatches)){
          $title = $aMatches[1];
          $sFound = 'h4';
        }
      }

      if($this->sBodyFilter!=""){
        preg_match($this->sBodyFilter, $body, $aMatches);
        if(sizeof($aMatches)>0){
          $body= $aMatches[1];
        }
      }
      $title = strip_tags($title);
      $title = html_entity_decode($title);

      //remove clutter
      $body = preg_replace("/<script.*?<\/script>/is", ' ', $body);
      $body = preg_replace("/<\!\-\-.*?\-\->/is", ' ', $body);
      $body = preg_replace("/\(/is", '', $body);
      $body = preg_replace("/\'/is", '', $body);
      $body = $this->sHtmlToRawText($body);
      $body = preg_replace("/\s+/is", ' ', $body);
      $body = strip_tags($body);

      //check for duplicates
      $md5 = md5($body);
      $result=mysql_query("SELECT url,md5 from document where md5='$md5'") or die(mysql_error());
      $row=mysql_fetch_row($result);
      if($row) {
        print "\r\nduplicate found for ".$url." -> ".$row['url'].", md5:".$row['md5']."\r\n";
        return false;
      }
      //add documents with content
      $blength=strlen($body);
      if($blength>5 && strlen($url)>0 ){
        $sSQL = "INSERT INTO document(account_id,url,title,content,md5, level) values('".$this->iAccountId."','$url','$title', '$body', '$md5', '$level');";
        print "indexing: [ $blength ] $url \r\n";
        mysql_query( $sSQL );
      }else{
        print $url." empty doc <br />\r\n";
      }
    }catch(Exception $e){
      print "failed adding $url\r\n";
    }
  }

  public function isUTF8($str)
  {
    $c=0; $b=0;
    $bits=0;
    $len=strlen($str);
    for($i=0; $i<$len; $i++){
      $c=ord($str[$i]);
      if($c > 128){
        if(($c >= 254)) return false;
        elseif($c >= 252) $bits=6;
        elseif($c >= 248) $bits=5;
        elseif($c >= 240) $bits=4;
        elseif($c >= 224) $bits=3;
        elseif($c >= 192) $bits=2;
        else return false;
        if(($i+$bits) > $len) return false;
        while($bits > 1){
          $i++;
          $b=ord($str[$i]);
          if($b < 128 || $b > 191) return false;
          $bits--;
        }
      }
    }
    return true;
  }

  public function sHtmlToRawText($sWord, $bNewLines=false, $bCleanHtml=false)
  {
    if ($bCleanHtml) {
      $sWord = preg_replace("/<br\s*?\/\>/", "\n", $sWord);
      $sWord = preg_replace("/<[^>]*?\>/", " ", $sWord);  // Remove all htmltags
    }
    if (!$bNewLines){
      $sWord = preg_replace("/\n/", " ", $sWord);
      $sWord = preg_replace("/\r/", " ", $sWord);
    }
    $sWord = preg_replace("/(\¤|\#|\"|\'|\*)/", "", $sWord);  // Remove all nonword characters
    return $sWord;
  }
};

?>
