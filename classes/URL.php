<?php
class URL
{
  public static function checkDuplicate($accountId, $url)
  {
    $res = mysql_query("SELECT url from document where url='$url' and account_id='".$acocuntId."'") or die(mysql_error());
    if($row=mysql_fetch_array($res)){
      print "duplicate :$url  -> ".$row['url']."\r\n";
      return  true;  
    }
    return false;
  }

  public static function filter($accountId, $url, $tablename)
  {
    if (strpos($url, "#")) {
      print "skip - 'contains HTML anchor' $url \r\n";
      return true;
    }  
    
    preg_match("|\@|", $url, $match);
    if ( count($match) > 0 ) {
      print "    skip ".$url. " - is an email \r\n";
      return true;
    }
 
    if ( strpos($url, "javascript:")) {
      print "    skip ".$url." - is a javascript link \r\n";
      return true;
    }

   $SQL ="select name,value from ".$tablename." where account_id='".$acocuntId."';";
   $res = mysql_query($SQL) or die ("SQL:".$SQL." failed:".mysql_error());
   while ($row = mysql_fetch_array($res) ){
     $name=$row[0];
     $item = urldecode($row[1]);
     if ($item!="") {
       preg_match("|$item|", $url, $match);
       if ( count($match) > 0){
         print "    skip due to :".$name." ".$item." \r\n";
         return true; 
       }
     }  
   }
   return false;
  }

  public static function extractHost($url)
  {
    preg_match("@(http\s?\://([^\/].*?))(\/|$)@", $url, $match);
    if ( count($match) > 1 ){
      $host = $match[2];
    }
    return $host;
  }

  public static function extractRelativeUrl($host, $url)
  {
    $url=preg_replace("/http\:\/\//i","", $url);
    $url=str_replace($host, "", $url);
    if($url==""){
      $url="/";
    }
    return $url;
  }

  public static function expandUrl($item, $parent)
  {
    $page="";
    if ($item == './'){
      $item = '/';
    }
    preg_match("@(http\s?\://[^\/].*?)(\/|$)@", $parent, $match);
    if ( count($match) > 0 ){
      $base = $match[1];
    }
    preg_match("@(http\s?\://[^\/].*?)\/([^\?]*?)(\?|$)@", $parent, $match);
    if ( count($match) > 0 ){
      $page = $match[2];
    }
    preg_match("|^http|", $item, $match);
    if ( count($match) > 0 ){
      return $item;
    }

    if ($page) {
      preg_match("|^\/$page|", $item, $match);
      if ( count($match) > 0 ){
        return $base.$item;
      }
      preg_match("|^$page|", $item, $match);
      if ( count ($match) > 0 ){
        return $base.'/'.$item;
      }
    }

    preg_match("|^\?|", $item, $match);
    if ( count($match) > 0 ){
      return $base.'/'.$page.$item;
    }
    $url = $base.'/'.$item;
    return $url;
  }
}
?>
