<?php
class Indexer
{
  private $accountId;

  public function __construct($accountId)
  {
    $this->accountId = $accountId;
  }

  public function start()
  {
    $this->clear();
    $this->index();
  }

  public function clear()
  {
    mysql_query("DELETE FROM document where account_id='".$this->accountId."'") or die (mysql_error());
  }

  public function index()
  {
    $SQL="select max(retrieved),url,contenttype,content,level from dump where account_id='".$this->accountId."' group by account_id,url";
    $res = mysql_query($SQL) or die (mysql_error());

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
      if(!(URL::filter($url, "indexerfilter", $this->accountId))) return false;

      //default to html if not pdf
      if($contenttype!="application/pdf"){
        $content = html_entity_decode($content, ENT_QUOTES);
        $title = $this->findHTMLTitle($content);
        $title = htmlentities($title, ENT_QUOTES);
        if($title==""){
          $title=$url;
        }
        $content = $this->cleanHTML($content);
      } else {
        $title = $url;
      }

      $md5 = md5($content);
      if(!($this->noDuplicateContent($md5))) return false;

      //TODO: combine sqltable 'dump' and 'document' 
      $length=strlen($content);
      if($length>5 && strlen($url)>0 ){
        $SQL = "INSERT INTO document(account_id,url,title,contenttype,content,md5, level) values('".$this->accountId."','$url','$title','$contenttype', '$content', '$md5', '$level');";
        print "indexing: [ ".$length." ] ".urldecode($url)." \r\n";
        mysql_query( $SQL ) or die (mysql_error());
      }else{
        print $url." empty doc <br />\r\n";
      }
    }catch(Exception $e){
      print "failed adding $url\r\n";
    }
  }

  private function noDuplicateURL( $url )
  {
   $res= mysql_query("SELECT url from document where url='$url' and account_id='".$this->accountId."'") or die(mysql_error());
   if($row=mysql_fetch_array($res)){
     print "duplicate: $url <br>  -> ".$row['url']."\r\n";
     return false;
   }
   return true;
  }
  private function noDuplicateContent($md5)
  {
    $result=mysql_query("SELECT url,md5 from document where md5='$md5' and account_id='".$this->accountId."'") or die(mysql_error());
    $row=mysql_fetch_row($result);
    if($row) {
      print "\r\nduplicate found for ".$url." -> ".$row['url'].", md5:".$row['md5']."\r\n";
      return false;
    }
   return true;
  }

  
  //TODO: create HTML utility class
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
    return $html;
  }

  private function findHTMLTitle($html)
  {
    $title='';
    if ($title == ''){
      preg_match("|<.*?content_header[^>]*?\>(.*?)<\/[^>]*?\>|is", $html, $matches);
      if(sizeof($matches)){
        $title = $matches[1];
      }
    }
    if ($title == ''){
      preg_match("|<h1>(.*?)<\/h1>|is", $html, $matches);
      if(sizeof($matches)){
        $title = $matches[1];
      }
    }
    if ($title == ''){
      preg_match("|<h2>(.*?)<\/h2>|is", $html, $matches);
      if(sizeof($matches)){
        $title = $matches[1];
      }
    }
    if ($title == ''){
      preg_match("|<title>(.*?)<\/title>|is", $html, $matches);
      if(sizeof($matches)){
        $title = $matches[1];
      }
    }
    if ($title == ''){
      preg_match("|<h3>(.*?)<\/h3>|is", $html, $matches);
      if(sizeof($matches)){
        $title = $matches[1];
      }
    }
    if ($title == ''){
      preg_match("|<h4>(.*?)<\/h4>|is", $html, $matches);
      if(sizeof($matches)){
        $title = $matches[1];
      }
    }
    return ($title);
  }
};
?>
