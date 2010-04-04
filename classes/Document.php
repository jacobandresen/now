<?php
class Document{

  public $level;
  public $url;
  public $contentType;
  public $content;

  public function save ($accountId) 
  { 
    if ($accountId==""){
      die ("coding error. save to empty account\r\n");
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
   $SQL= "INSERT IGNORE into document(account_id, url, contenttype, content, level) values('".$accountId."','".$this->url."','".$this->contentType."','".$this->content."','".$this->level."')";
   mysql_query($SQL) or die("SQL error:".$SQL." \r\nfailed to insert into document:".mysql_error());
   return true;  
  } 

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
