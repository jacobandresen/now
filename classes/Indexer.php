<?php
class Indexer
{
  private $iAccountId;
  private $filterSettings;
  private $bodyFilter;

  public function __construct($iAccountId)
  {
    $this->iAccountId=$iAccountId;
    $this->sBodyFilter="";
  //  $this->filterSettings=Setting::mkSettings("indexerfilter", $this->iAccountId);
  }

  public function start()
  {
    $this->clear();
    $this->index();
  }


  public function clear()
  {
    mysql_query("DELETE FROM document where account_id='".$this->iAccountId."'") or die (mysql_error());
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

      if($this->filterSettings) {
        foreach ($this->filterSettings as $setting){
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

     // if (Document::isUTF8($body)){
     //   $body = iconv("UTF-8", "ISO-8859-1", $body);
     // }

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
};
?>
