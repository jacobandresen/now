<?php

include("Result.php");

class Searcher {
  protected $iCustomerId;
  public $iLimit = 5;

  public function __construct($iCustomerId) {
    $this->iCustomerId=$iCustomerId;
  }

  function search_OLD ($query) {
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
  function aSearch ($query, $iPage) {
    $aRet = array();
		$sLimit = '';
		if ($iPage != 0){
	    $iOffset = ($iPage*$this->iLimit) - $this->iLimit;
	    $sLimit = " LIMIT " . $this->iLimit . " OFFSET $iOffset";
	  }
    if($query!=""){
      $result = mysql_query("SELECT *, MATCH(content) AGAINST('$query') AS score FROM document WHERE MATCH(content) AGAINST('$query') and user_id='".$this->iCustomerId."' ORDER BY score DESC ".$sLimit); 
      while ($row=mysql_fetch_array($result)){
        $title=$row['title'];
        $title=htmlentities($title); 
        $content=$row['content'];
        $content=htmlentities($content);
        $oResult = new Result();
        $oResult->sUrl = $row['url'];
        $oResult->sTitle = $title;
        $oResult->sContent = substr($content, 1, 400);
        $aRet[] = $oResult;
      }
    }
    return $aRet;
  }
  function iSearch ($query) {
    $aRet = array();
    if ($query != "") {
      $sSQL = "SELECT count(title) AS cnt 
               FROM document 
               WHERE MATCH(content) AGAINST('$query') and user_id='".$this->iCustomerId."'";
	    $result = mysql_query( $sSQL ) or die ( mysql_error());
      $row = mysql_fetch_array($result);
      return $row['cnt'];
    }
    return 0;
  }
}

?>
