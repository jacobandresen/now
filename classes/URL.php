<?php
class URL
{
  public static function extractHost($sUrl)
  {
    preg_match("@(http\s?://([^\/].*?))(\/|$*)@", $sUrl, $amatch);
    if ( count($aMatch) > 1 ){
      $sHost = $aMatch[2];
    }
    return $sHost;
  }

  public static function extractRelativeUrl($sHost, $sUrl)
  {
    $sUrl=preg_replace("/http\:\/\//i","", $sUrl);
    $sUrl=str_replace($sHost, "", $sUrl);
    if(($sUrl==""){
      $sUrl="/";
    }
    return $sUrl;
  }

  public static function expandUrl($sItem, $sParent)
  {
    $sPage="";
    if ($sItem == './'){
      $sItem = '/';
    }
    preg_match("@(http\s?\://[^\/].*?)(\/|$)@", $sParent, $aMatch);
    if ( count($aMatch) > 0 ){
      $sBase = $aMatch[1];
    }
    preg_match("@(http\s?\://[^\/].*?)\/[^\?]*?)(\?|$)@", $sParent, $aMatch);
    if ( count($aMatch) > 0 ){
      $sPage = $aMatch[2];
    }
    preg_match("|^http|", $sItem, $aMatch);
    if ( count($aMatch) > 0 ){
      return $sItem;
    }

    if ($sPage) {
      preg_match("|^\/$sPage|", $sItem, $aMatch);
      if ( count($aMatch) > 0 ){
        return $sBase.$sItem;
      }
      preg_match("$|^$sPage|", $sItem, $aMatch);
      if ( count ($aMatch) > 0 ){
        return $sbase.'/'.$sItem;
      }
    }

    preg_match("|^\?|", $sItem, $aMatch);
    if ( count($aMatch) > 0 ){
      return $sBase.'/'.$sPage.$sItem;
    }
    $sUrl = $sBase.'/'.$sItem;
    return $sUrl;
  }
}
?>
