<?php
class Indexer
{
  private $iAccountId;
  private $filterSettings;

  public function __construct($iAccountId)
  {
    $this->iAccountId = $iAccountId;

    $this->filterSettings=array();
    $res = mysql_query('select name,value from indexerfilter where account_id="'.$this->iAccountId.'"');
    while ($row = mysql_fetch_array($res) ) {
      $setting = new Setting();
      $setting->name=$row[0];
      $setting->value=$row[1];
      array_push($this->filterSettings, $setting);
    }
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
    $sSQL="select max(retrieved),url,contenttype,content,level from dump where account_id='".$this->iAccountId."' group by account_id,url";
    $res = mysql_query($sSQL) or die (mysql_error());

    while($row=mysql_fetch_array($res)){
      try{
        $this->add($row['url'],
          $row['contenttype'],
          $row['content'],
          $row['level']);
      }catch(Exception $e){
        print "FAILED: $url \r\n";
      }
    }
  }

  public function add($url, $contenttype,$content, $level)
  {
    print "ADD: ".urldecode($url)." \r\n";
    try{
      $title="";

      if(!($this->noDuplicateURL($url))) return false;
      if(!($this->URLFilter($url))) return false;

      $content = html_entity_decode($content, ENT_QUOTES);
      if(!strpos($content,".pdf")){
        $title = $this->findHTMLTitle($content);
        $title = htmlentities($title, ENT_QUOTES);

        print "TITLE:".$title."\r\n";
        $content = $this->cleanHTML($content);
      } else {
        $title = $url;
      }

      $md5 = md5($content);
      if(!($this->noDuplicateContent($md5))) return false;


      $blength=strlen($content);
      if($blength>5 && strlen($url)>0 ){
        $sSQL = "INSERT INTO document(account_id,url,title,contenttype,content,md5, level) values('".$this->iAccountId."','$url','$title','$contenttype', '$content', '$md5', '$level');";
        print "indexing: [ $blength ] ".urldecode($url)." \r\n";
        mysql_query( $sSQL ) or die (mysql_error());
      }else{
        print $url." empty doc <br />\r\n";
      }
    }catch(Exception $e){
      print "failed adding $url\r\n";
    }
  }

  private function URLFilter($url)
  {
    $url=urldecode($url);
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
   return true;
  }

  private function noDuplicateURL( $url )
  {
   $res= mysql_query("SELECT url from document where url='$url' and account_id='".$this->iAccountId."'") or die(mysql_error());
   if($row=mysql_fetch_array($res)){
     print "duplicate: $url <br>  -> ".$row['url']."\r\n";
     return false;
   }
   return true;
  }
  private function noDuplicateContent($md5)
  {
    $result=mysql_query("SELECT url,md5 from document where md5='$md5'") or die(mysql_error());
    $row=mysql_fetch_row($result);
    if($row) {
      print "\r\nduplicate found for ".$url." -> ".$row['url'].", md5:".$row['md5']."\r\n";
      return false;
    }
   return true;
  }

  private function cleanHTML($html)
  {
    $html = preg_replace("/<script.*?<\/script>/is", ' ', $html);
    $html = preg_replace("/<link.*?\/>/is", ' ', $html);
    $html = preg_replace("/<style type\=\"text\/css\">.*?<\/style>/is", '', $html);
    $html = preg_replace("/<\!\-\-.*?\-\->/is", ' ', $html);
    $html = preg_replace("/\(/is", '', $html);
    $html = preg_replace("/\'/is", '', $html);
    $html = preg_replace("/\s+/is", ' ', $html);
    $html = preg_replace("/<.*?>/is", '', $html);
    $html = strip_tags($html);
  //  $html = htmlentities($html, ENT_QUOTES);
    return $html;
  }

  private function findHTMLTitle($body)
  {
    $title='';
    if ($title == ''){
      preg_match("|<.*?content_header[^>]*?\>(.*?)<\/[^>]*?\>|is", $body, $aMatches);
      if(sizeof($aMatches)){
        $title = $aMatches[1];
      }
    }
    if ($title == ''){
      preg_match("|<h1>(.*?)<\/h1>|is", $body, $aMatches);
      if(sizeof($aMatches)){
        $title = $aMatches[1];
      }
    }
    if ($title == ''){
      preg_match("|<h2>(.*?)<\/h2>|is", $body, $aMatches);
      if(sizeof($aMatches)){
        $title = $aMatches[1];
      }
    }
    if ($title == ''){
      preg_match("|<title>(.*?)<\/title>|is", $body, $aMatches);
      if(sizeof($aMatches)){
        $title = $aMatches[1];
      }
    }
    if ($title == ''){
      preg_match("|<h3>(.*?)<\/h3>|is", $body, $aMatches);
      if(sizeof($aMatches)){
        $title = $aMatches[1];
      }
    }
    if ($title == ''){
      preg_match("|<h4>(.*?)<\/h4>|is", $body, $aMatches);
      if(sizeof($aMatches)){
        $title = $aMatches[1];
      }
    }
    return ($title);
  }
};
?>
