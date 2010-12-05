<?php
class ExtJs
{
  public static function cacheFly ()
  {
    $cacheFly = "http://extjs.cachefly.net/ext-3.2.1";

    print "  <link rel=\"stylesheet\" type=\"text/css\" href=\"$cacheFly/resources/css/ext-all.css\"/>\r\n";
    print "  <script type=\"text/javascript\" src=\"$cacheFly/adapter/ext/ext-base.js\"></script>\r\n";
    print "  <script type=\"text/javascript\" src=\"$cacheFly/ext-all-debug.js\"></script>\r\n";
  }

  public static function import($component)
  {
    print "  <script type\"text/javascript\" src=\"extjs/$component.ui.js\"></script>\r\n";
    print "  <script type\"text/javascript\" src=\"extjs/$component.js\"></script>\r\n";
  }

  public static function shouldRead($file) 
  {
     return ($file != "." && $file != ".." 
            && (strpos($file, ".swp") == false)   
            && (strpos($file, "xds") === false));  
  }

}
?>
