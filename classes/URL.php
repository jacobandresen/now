<?php
class URL
{
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
