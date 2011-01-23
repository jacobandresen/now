<?php
class Document{
  
  public $id;
  public $parentId;
  public $level;
  public $url;
  public $title;
  public $contentType;
  public $content;

  public function __construct () 
  {
  }

  public function save ($parentId)
  {
    if ($parentId==""){
      die ("coding error. trying to save to empty collection\r\n");
    }

    if (strlen($this->url)>1028) {
       return false;
     }
    if (strlen($this->content)>MAX_CONTENT_LENGTH){
       return false;
    }
    if (strlen($this->content)<1){
      return false;
    }

    $this->url = urlencode($this->url);
    $SQL= "INSERT IGNORE into document(parent_id, url, content_type, content, level) values('".$parentId."','".$this->url."','".$this->contentType."','".$this->content."','".$this->level."')";
    mysql_query($SQL) or die("SQL error:".$SQL." \r\nfailed to insert into document:".mysql_error());
    return true;
  }

  //TODO: rename to hasTextContent?
  public function shouldCrawl()
  {
    if (
      ($this->contentType == "application/x-zip") ||
      ($this->contentType == "application/xml") ||
      ($this->contentType == "application/json") ||
      ($this->contentType == "image/jpeg") ||
      ($this->contentType == "image/jpg") ||
      ($this->contentType == "image/bmp") ||
      ($this->contentType == "image/png") ||
      ($this->contentType == "text/css") ||
      ($this->contentType == "text/javascript")
    ) {
      return false;
    }
    return true;
  }

};
?>
