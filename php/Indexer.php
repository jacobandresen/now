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
    $SQL = "DELETE FROM facet where collection_id='".$this->collection->id."'";
     mysql_query($SQL) or die (mysql_error());
         
    $SQL="select max(retrieved),id,url,contenttype,content,level from document where collection_id='".$this->collection->id."' group by owner_id,url";
    $res = mysql_query($SQL) or die (mysql_error());

    while($row=mysql_fetch_array($res)){
      $document = new Document();
      $document->id = $row['id'];
      $document->url = $row['url'];
      $document->contentType= $row['contenttype'];
      $document->content =  $row['content'] ;
      $document->level =$row['level'];

      $this->analyzeDocument($document);
    }
  }

  protected function analyzeDocument($document)
  {
    try{
      $title="";

      if($document->contentType!="application/pdf"){
        
        //analyze HTML as default
        $document->content = html_entity_decode($document->content, ENT_QUOTES);
        $document->title = HTMLRobot::findTitle($this->collection->ownerId, $document->content);
        $document->title = htmlentities($document->title, ENT_QUOTES);
        $document->content = HTMLRobot::clean($document->content);
      }
      
      //default rules
      if($document->title==""){ $document->title=$document->url; }

      $md5 = md5($document->content);
      $this->setMD5($document->id, $md5);
   
      $this->saveFacets($document);

    }catch(Exception $e){
      $this->collection->log("failed adding $document->url ");
    }
  }

  protected function saveFacets( $document ) 
  {
    $length=strlen($document->content);
    $this->collection->log("[".$length."]INDEX ".$document->url);

    if($length>0 && strlen($document->url)>0 ){

        $SQL = "INSERT INTO facet(owner_id,collection_id,document_id,name,content) values('".$this->collection->ownerId."','".$this->collection->id."','".$document->id."','title','".$document->title."');";
        mysql_query( $SQL ) or die (mysql_error());

        $SQL = "INSERT INTO facet(owner_id,document_id,name,content) values('".$this->collection->ownerId."','".$this->collection->id."','".$document->id."','content','".$document->content."');";
        mysql_query( $SQL ) or die (mysql_error());

     }else{
        $this->collection->log($document->url." empty doc");
      }
  }
  

  private function setMD5 ($id, $md5)
  {
    $SQL = "update document where id='".$id."' set md5='".$md5."'";
    mysql_query($SQL);
  }
};
?>
