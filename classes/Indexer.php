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

      if($contenttype!="application/pdf"){
        $content = html_entity_decode($content, ENT_QUOTES);
        $title = HTMLRobot::findTitle($this->accountId, $content);
        $title = htmlentities($title, ENT_QUOTES);
        if($title==""){
          $title=$url;
        }
        $content = HTMLRobot::clean($content);
      } else {
        $title = $url;
      }

      $md5 = md5($content);
      if($this->checkDuplicateContent($accountId, $md5)) return false;
      $this->update_index_info($id, $md5);

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

  private function update_index_info ($id, $md5) 
  {
    $SQL = "delete from index_info where document_id='".$id."';" ;
    mysql_query( $SQL ) or die ("delete index info failed:".mysql_error()); 
    $SQL = "insert into index_info(document_id, account_id,md5) values('$id','$this->accountId','$md5');" ;
    mysql_query($SQL) or die ("update index info failed:".mysql_error());
  }

  private function checkDuplicateContent($accountId, $md5)
  {
    $result = mysql_query("SELECT d.url, i.md5 from document d, index_info i where i.md5='$md5' and i.document_id=d.id and d.account_id='".$acountId."'") or die(mysql_error());
    $row=mysql_fetch_row($result);
    if ($row) {
      print "duplicate found for ".$row['url']."\r\n";
      return true;
    }
   return false; 
  }
};
?>
