<?php
class Indexer extends Collection
{
  public function __construct($ownerId)
  {
    $this->ownerId = $ownerId;
  }

  public function start()
  {
    $this->clear();
    $this->index();
  }

  public function clear()
  {
   mysql_query("DELETE FROM facet where owner_id='".$this->ownerId."'") or die (mysql_error());
  }

  public function index()
  { 
    //FIXME: this should be for the current collection (not the ownerId)
    $SQL="select max(retrieved),id,url,contenttype,content,level from document where owner_id='".$this->ownerId."' group by account_id,url";
    $res = mysql_query($SQL) or die (mysql_error());

    while($row=mysql_fetch_array($res)){
      $document = new Document($row['id'], $row['url'], $row['contentType'], $row['content'] ,$row['level']);
      $this->add($document);
    }
  }

  public function add($document)
  {
    try{
      $title="";

      if(URL::hasDuplicate($this->ownerId, $document->url)) return false;
      if(URL::filter($this->ownerId, $this->getDomainId($document->url), $document->url, "indexerfilter")) return false;

      if($document->contenttype!="application/pdf"){
        //default to HTML
        $document->content = html_entity_decode($document->content, ENT_QUOTES);
        $document->title = HTMLRobot::findTitle($this->ownerId, $document->content);
        $document->title = htmlentities($document->title, ENT_QUOTES);
        $document->content = HTMLRobot::clean($document->content);
      }

      //default title
      if($document->title==""){ $document->title=$document->url; }

      $md5 = md5($document->content);
      if($Document::hasDuplicateContent($accountId, $md5)) return false;
      $this->setMD5($document->id, $md5);

      $length=strlen($document->content);
      if($length>0 && strlen($document->url)>0 ){

       $SQL = "INSERT INTO facet(account_id,document_id,name,content) values('".$this->accountId."','".$document->id."','title','".$document->title."');";
       mysql_query( $SQL ) or die (mysql_error());

       $SQL = "INSERT INTO facet(account_id,document_id,name,content) values('".$this->accountId."','".$document->id."','content','".$document->content."');";
       mysql_query( $SQL ) or die (mysql_error());

     }else{
        print $document->url." empty doc <br />\r\n";
      }
    }catch(Exception $e){
      print "failed adding $document->url\r\n";
    }
  }

  private function setMD5 ($id, $md5)
  {
    $SQL = "update document where id='".$id."' set md5='".$md5."'";
  }
};
?>
