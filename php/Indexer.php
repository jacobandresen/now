<?php
class Indexer 
{
  public $collection;

  public function __construct($params)
  {
    $this->collection = Collection::read($params); 
  }

  public function start()
  {
    $this->clear();
    $this->index();
  }

  public function clear()
  {
   mysql_query("DELETE FROM facet where owner_id='".$this->collection->ownerId."'") or die (mysql_error());
  }

  public function index()
  { 
    //FIXME: this should be for the current collection (not the ownerId)
    $SQL="select max(retrieved),id,url,contenttype,content,level from document where owner_id='".$this->collection->ownerId."' group by owner_id,url";
    $res = mysql_query($SQL) or die (mysql_error());

    while($row=mysql_fetch_array($res)){
      $document = new Document($row['id'], $row['url'], $row['contenttype'], $row['content'] ,$row['level']);
      $this->add($document);
    }
  }

  public function add($document)
  {
    try{
      $title="";

//      if(URL::hasDuplicate($this->collection->ownerId, $document->url)) return false;
 //     if(URL::filter($this->collection->ownerId, $this->collection->getDomainId($document->url), $document->url, "indexerfilter")) return false;

      if($document->contentType!="application/pdf"){
        //default to HTML
        $document->content = html_entity_decode($document->content, ENT_QUOTES);
        $document->title = HTMLRobot::findTitle($this->collection->ownerId, $document->content);
        $document->title = htmlentities($document->title, ENT_QUOTES);
        $document->content = HTMLRobot::clean($document->content);
      }

      //default title
      if($document->title==""){ $document->title=$document->url; }

      $md5 = md5($document->content);
   //   if(Document::hasDuplicateContent($this->collection->ownerId, $md5)) return false;
      $this->setMD5($document->id, $md5);


      $length=strlen($document->content);
      $this->collection->log("[".$length."]INDEX ".$document->url);
      if($length>0 && strlen($document->url)>0 ){

       $SQL = "INSERT INTO facet(owner_id,document_id,name,content) values('".$this->collection->ownerId."','".$document->id."','title','".$document->title."');";
       mysql_query( $SQL ) or die (mysql_error());

       $SQL = "INSERT INTO facet(owner_id,document_id,name,content) values('".$this->collection->ownerId."','".$document->id."','content','".$document->content."');";
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
