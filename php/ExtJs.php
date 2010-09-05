<?php
class ExtJs
{
  public static function includeCacheFly ()
  {
    $cacheFly = "http://extjs.cachefly.net/ext-3.2.1";

    print "  <link rel=\"stylesheet\" type=\"text/css\" href=\"$cacheFly/resources/css/ext-all.css\"/>\r\n";
    print "  <script type=\"text/javascript\" src=\"$cacheFly/adapter/ext/ext-base.js\"></script>\r\n";
    print "  <script type=\"text/javascript\" src=\"$cacheFly/ext-3.2.1/ext-all-debug.js\"></script>\r\n";
  }
  
  public static function includeDirectory ($directory)
  {
    if ($handle = opendir($directory))
    {
       while (false !== ($file = readdir($handle)))
       {
          if ($file != "." && $file != ".." && (strpos($file, "xds") === false))  
            print "  <script type=\"text/javascript\" src=\"".$file."\"></script>\r\n";
       } 
    }
  }
}
?>
