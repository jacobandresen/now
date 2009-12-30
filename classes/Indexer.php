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
   mysql_query("DELETE FROM facet where account_id='".$this->accountId."'") or die (mysql_error());
  }

  public function index()
  {
    $SQL="select max(retrieved),id,url,contenttype,content,level from document where account_id='".$this->accountId."' group by account_id,url";
    $res = mysql_query($SQL) or die (mysql_error());

    while($row=mysql_fetch_array($res)){
      try{
        $this->add(
          $row['id'], 
          $row['url'],
          $row['contenttype'],
          $row['content'],
          $row['level']);
      }catch(Exception $e){
        print "FAILED: $url \r\n";
      }
    }
  }

  public function add($id, $url, $contenttype,$content, $level)
  {
    try{
      $title="";

      if(URL::checkDuplicate($this->accountId, $url)) return false;
      if(URL::filter($this->accountId, $url, "indexerfilter")) return false;

      
      //default to html if not pdf
      if($contenttype!="application/pdf"){
        $content = html_entity_decode($content, ENT_QUOTES);
        $title = HTMLUtil::findTitle($this->accountId, $content);
        $title = htmlentities($title, ENT_QUOTES);
        if($title==""){
          $title=$url;
        }
        $content = HTMLUtil::clean($content);
        print "ADD HTML [".strlen($content)."]: ".urldecode($url)." \r\n";
      } else {
        $title = $url;
        print "ADD HTML [".strlen($content)."]: ".urldecode($url)." \r\n";
      }

      $md5 = md5($content);
      if(HTMLUtil::checkDuplicateContent($accountId, $md5)) return false;

      $length=strlen($content);
      if($length>0 && strlen($url)>0 ){
       $SQL = "INSERT INTO facet(account_id,document_id,name,content) values('".$this->accountId."','".$id."','title','".$title."');";   
       mysql_query( $SQL ) or die (mysql_error());

       $SQL = "INSERT INTO facet(account_id,document_id,name,content) values('".$this->accountId."','".$id."','content','".$content."');";   
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
