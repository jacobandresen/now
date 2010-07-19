<?php
class YASE_Indexer
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
      $document = new YASE_Document($row['id'], $row['url'], $row['contentType'], $row['content'] ,$row['level']);
      $this->add($document);
    }
  }

  //TODO: add flexible content analyzers (instead of hardcoded "title" and "content" facet 
  //TODO: update number of indexed documents 
  public function add($document)
  {
    try{
      $title="";

      if(YASE_URL::hasDuplicate($this->accountId, $document->url)) return false;
      if(YASE_URL::filter($this->accountId, $document->url, "indexerfilter")) return false;

      if($document->contenttype!="application/pdf"){
        //default to HTML
        $document->content = html_entity_decode($document->content, ENT_QUOTES);
        $document->title = YASE_HTMLRobot::findTitle($this->accountId, $document->content);
        $document->title = htmlentities($document->title, ENT_QUOTES);
        $document->content = YASE_HTMLRobot::clean($document->content);
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
