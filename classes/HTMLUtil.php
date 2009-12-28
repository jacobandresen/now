<?php
class HTMLUtil
{
  public static function noDuplicateURL( $accountId, $url )
  {
   $res= mysql_query("SELECT url from document where url='$url' and account_id='".$accountId."'") or die(mysql_error());
   if($row=mysql_fetch_array($res)){
     print "duplicate: $url <br>  -> ".$row['url']."\r\n";
     return false;
   }
   return true;
  }
  public static function noDuplicateContent($accountId, $md5)
  {
    $result=mysql_query("SELECT url,md5 from document where md5='$md5' and account_id='".$accountId."'") or die(mysql_error());
    $row=mysql_fetch_row($result);
    if($row) {
      print "\r\nduplicate found  ".$url." -> ".$row['url'].", md5:".$row['md5']."\r\n";
      return false;
    }
   return true;
  }

  public static function clean($html)
  {
    $html = preg_replace("/<script.*?<\/script>/is", ' ', $html);
    $html = preg_replace("/<link.*?\/>/is", ' ', $html);
    $html = preg_replace("/<style type\=\"text\/css\">.*?<\/style>/is", '', $html);
    $html = preg_replace("/<\!\-\-.*?\-\->/is", ' ', $html);
    $html = preg_replace("/\(/is", '', $html);
    $html = preg_replace("/\'/is", '', $html);
    $html = preg_replace("/\s+/is", ' ', $html);
    $html = preg_replace("/<.*?>/is", '', $html);
    $html = strip_tags($html);
    return $html;
  }

  public static function findTitle($html)
  {
    $title='';
    if ($title == ''){
      preg_match("|<.*?content_header[^>]*?\>(.*?)<\/[^>]*?\>|is", $html, $matches);
      if(sizeof($matches)){
        $title = $matches[1];
      }
    }
    if ($title == ''){
      preg_match("|<h1>(.*?)<\/h1>|is", $html, $matches);
      if(sizeof($matches)){
        $title = $matches[1];
      }
    }
    if ($title == ''){
      preg_match("|<h2>(.*?)<\/h2>|is", $html, $matches);
      if(sizeof($matches)){
        $title = $matches[1];
      }
    }
    if ($title == ''){
      preg_match("|<title>(.*?)<\/title>|is", $html, $matches);
      if(sizeof($matches)){
        $title = $matches[1];
      }
    }
    if ($title == ''){
      preg_match("|<h3>(.*?)<\/h3>|is", $html, $matches);
      if(sizeof($matches)){
        $title = $matches[1];
      }
    }
    if ($title == ''){
      preg_match("|<h4>(.*?)<\/h4>|is", $html, $matches);
      if(sizeof($matches)){
        $title = $matches[1];
      }
    }
    return ($title);
  }
};
?>
