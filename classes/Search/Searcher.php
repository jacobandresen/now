<?php
//2009, Jacob Andresen <jacob.andresen@gmail.com>
class Searcher {
  protected $iCustomerId;

  public function __construct($sLogin) {
    if (!(isset($sLogin))){
      die("search:invalid login \r\n");
    }
    $res = mysql_query("select id from user where login='".$sLogin."'") or die(mysql_error()); 
    if($row=mysql_fetch_array($res)){
     $this->iCustomerId=$row['id'];
    }else{
     $this->iCustomerId=-1;
    }
  }

  function search ($query) {
    if($query!=""){
      $result = mysql_query("SELECT *, MATCH(content) AGAINST('$query') AS score FROM document WHERE MATCH(content) AGAINST('$query') and user_id='".$this->iCustomerId."' ORDER BY score DESC"); 
      print "<ul>\r\n";     
      while ($row=mysql_fetch_array($result)){
        $title=$row['title'];
        $title=htmlentities($title); 
        $content=$row['content'];
        $content=htmlentities($content);
        print "\t<li><a href=\"".$row['url']."\">".$title."</a><br/>\r\n";       print substr($content, 1, 400);
        print "</li>\r\n";
      }
      print "</ul>\r\n";   
    } 
  }
}
?>
