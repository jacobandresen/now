<?php
class Document{
  public $level;
  public $url;
  public $title;
  public $contentType;
  public $content;

  public function save ($accountId) 
  { 
    if ($accountId==""){
      die ("coding error. save to empty account\r\n");
    } 

    if (strlen($this->url)>1028) {
       print "    skip - $url 'URL too long'\r\n";
       return false;
     }
    if (strlen($this->content)>MAX_CONTENT_LENGTH){ 
       print "    skip - $url 'content too big'\r\n";
       return false;
    } 
    if (strlen($this->content)<1){
      print "    skip - $url 'no content' \r\n";
      return false;
    }

    print "    add [".$this->level."] - ".$this->url." ".strlen($this->content)." ".MAX_CONTENT_LENGTH."\r\n";
   $this->url = urlencode($this->url);

   $SQL= "INSERT IGNORE into dump(account_id, url, contenttype, content, level) values('".$accountId."','".$this->url."','".$this->contenttype."','".$this->content."','".$this->level."')";
   mysql_query($SQL) or die("SQL error:".$SQL." \r\nfailed to insert into dump:".mysql_error());
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
      print "    skip - ".$this->url." 'do not crawl' ".$this->contentType."\r\n";
      return false;
    }
    return true;
  }

  public static function isUTF8($string)
  {
    $c=0; $b=0;
    $bits = 0;
    $len = strlen($string);
    for($i=0;$i<$len; $i++)
    {
      $c=ord($string[$i]);
      if ($c > 128) {
        if ($c >= 254) return false;
        elseif($c >= 252) $bits = 6;
        elseif($c >= 248) $bits = 5;
        elseif($c >= 240) $bits = 4;
        elseif($c >= 224) $bits = 3;
        elseif($c >= 192) $bits = 2;
        else return false;
        if (($i+$bits) > $len) return false;
        while($bits > 1){
          $i++;
          $b=ord($str[$i]);
          if ($b < 128 || $b > 191) return false;
          $bits--;
        }
      }
    }
    return true;
  }
};
?>
