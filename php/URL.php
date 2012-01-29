<?php
class URL
{
    public static function hasDuplicate($collectionId, $url)
    {
        $res = mysql_query("SELECT url from document where url='$url' and parent_id='" . $collectionId . "'") or die(mysql_error());
        if ($row = mysql_fetch_array($res)) {
            return true;
        }
        return false;
    }

    public static function filter($url)
    {
        $url = strtolower($url);

        if (strpos($url, "#")) {
            return true;
        }

        preg_match("|\@|", $url, $match);
        if (count($match) > 0) {
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

        return false;
    }

    public static function extractHost($url)
    {
        $url = str_replace("\/", "/", $url);
        preg_match("@(https?\://([^\/].*?))(\/|$)@", $url, $match);
        if (count($match) > 1) {
            $host = $match[2];
        }
        if (!isset($host)) {
            print "missing HOST for URL: $url \r\n";
        }
        return $host;
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
