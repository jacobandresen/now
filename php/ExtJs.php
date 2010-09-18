<?php
class ExtJs
{
  public static function includeCacheFly ()
  {
    $cacheFly = "http://extjs.cachefly.net/ext-3.2.1";

    print "  <link rel=\"stylesheet\" type=\"text/css\" href=\"$cacheFly/resources/css/ext-all.css\"/>\r\n";
    print "  <script type=\"text/javascript\" src=\"$cacheFly/adapter/ext/ext-base.js\"></script>\r\n";
    print "  <script type=\"text/javascript\" src=\"$cacheFly/ext-all-debug.js\"></script>\r\n";
  }

  public static function includeDesignerDirectory ($directory)
  {
    $contents = file_get_contents($directory."/xds_includeOrder.txt");
    $contents = str_replace("src=\"", "src=\"$directory/", $contents);
    print $contents;
  }
   
  public static function shouldRead($file) 
  {
     return ($file != "." && $file != ".." 
            && (strpos($file, ".swp") == false)   
            && (strpos($file, "xds") === false));  
  }

}
?>
