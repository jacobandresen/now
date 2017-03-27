<?php
class URL
{
    public static function hasDuplicate($collectionId, $url)
    {
        $res = pg_query("SELECT url from document where url='$url' and parent_id='" . $collectionId . "'");
        if ($row = pg_fetch_array($res)) {
            return true;
        }
        return false;
    }

    public static function filter($domainId, $url, $name)
    {
        $url = strtolower($url);

        preg_match("|\@|", $url, $match);
        if (count($match) > 0) {
            return true;
        }

        if (strpos($url, ".js")) {
            return true;
        }

        if (strpos($url, "javascript:")) {
            return true;
        }

        if (strpos($url, ".jpg")) {
            return true;
        }

        if (strpos($url, ".gif")) {
            return true;
        }

        if (strpos($url, ".bmp")) {
            return true;
        }

        if (strpos($url, ".png")) {
            return true;
        }
        return false;
    }

    public static function extractHost($url)
    {
        $match = array();
        preg_match("@(https?\://([^\/].*?))(\/|$)@", $url, $match);
        if (sizeof($match)>1) {
            return $match[2];
        } else {
            return "";
        }
    }

    public static function extractRelativeUrl($host, $url)
    {
        $url = preg_replace("/http\:\/\//i", "", $url);
        $url = str_replace($host, "", $url);
        if ($url == "") {
            $url = "/";
        }
        return $url;
    }

    public static function expandUrl($item, $parent)
    {
       $page = "";
       if ($item == './') {
           $item = '/';
       }
      preg_match("@(http\s?\://[^\/].*?)(\/|$)@", $parent, $match);
      if (count($match) > 0) {
         $base = $match[1];
      }
      preg_match("@(http\s?\://[^\/].*?)\/([^\?]*?)(\?|$)@", $parent, $match);
      if (count($match) > 0) {
          $page = $match[2];
      }
      preg_match("|^http|", $item, $match);
      if (count($match) > 0) {
          return $item;
      }

      if ($page) {
          preg_match("|^\/$page|", $item, $match);
          if (count($match) > 0) {
              return $base . $item;
          }
          preg_match("|^$page|", $item, $match);
          if (count($match) > 0) {
              return $base . '/' . $item;
          }
      }

      preg_match("|^\?|", $item, $match);
      if (count($match) > 0) {
          return $base . '/' . $page . $item;
      }
      $url = $base . '/' . $item;
      return $url;
    }

    public static function inDomain($url, $domain)
    {
        $host = URL::extractHost($url);
        $pos = strpos($url, $domain);
        return ($pos !== false);
    }
}
?>
