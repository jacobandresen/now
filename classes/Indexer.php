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

      if(!(HTMLUtil::noDuplicateURL($this->accountId, $url))) return false;
      if(!(URL::filter($url, "indexerfilter", $this->accountId))) return false;

      //default to html if not pdf
      if($contenttype!="application/pdf"){
        $content = html_entity_decode($content, ENT_QUOTES);
        $title = HTMLUtil::findTitle($this->accountId, $content);
        $title = htmlentities($title, ENT_QUOTES);
        if($title==""){
          $title=$url;
        }
        $content = HTMLUtil::clean($content);
      } else {
        $title = $url;
      }

      $md5 = md5($content);
      if(!(HTMLUtil::noDuplicateContent($accountId, $md5))) return false;

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
};
?>
