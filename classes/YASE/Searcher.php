<?php
/**
 * Search the YASE database and return results in the internal YASE format 
 * 
 * author: Johan Bäckström
 * author: Jacob Andresen
 */
class YASE_Searcher 
{
    protected $iAccountId;
    public $iLimit = 5;

    public function __construct($iAccountId) 
    {
        $this->iAccountId=$iAccountId;
    }

    
    /**
     * Perform a search and obtain the result
     * @param $query : query string issued from UI
     * @param $iPage : result listing paging number (optional)  
     * @return: a list of YASE_Result
     */   
    public function aSearch ($query, $iPage) 
    {
        $aRet = array();
		$sLimit = '';
		if ($iPage != 0){
	        $iOffset = ($iPage*$this->iLimit) - $this->iLimit;
	        $sLimit = " LIMIT " . $this->iLimit . " OFFSET $iOffset";
	    }
    
        if($query!=""){
            $result = mysql_query("SELECT *, MATCH(content) AGAINST('$query') AS score FROM document WHERE MATCH(content) AGAINST('$query') and account_id='".$this->iAccountId."' ORDER BY score DESC ".$sLimit); 
            while ($row=mysql_fetch_array($result)){
                $title=$row['title'];
                $content=$row['content'];
       	        $content = preg_replace('/\&.*?\;/is',' ', $content); 
        
	            $oResult = new YASE_Result();
                $oResult->sUrl = urldecode($row['url']);
                $oResult->sTitle = trim(html_entity_decode($title));
                if($oResult->sTitle==""){ $oResult->sTitle = $oResult->sUrl; }  
                $oResult->sContent = substr($content, 1, 400);
                $aRet[] = $oResult;
            }
        }
        return $aRet;
    }

    /**
     *obtain the number of search results for the query
     *
     *NOTE: could we just use the length of the array returned in aSearch? /jka 
     */
    public function iSearch ($query) 
    {
        $aRet = array();
        if ($query != "") {
            $sSQL = "SELECT count(title) AS cnt 
               FROM document 
               WHERE MATCH(content) AGAINST('$query') and account_id='".$this->iAccountId."'";
	    $result = mysql_query( $sSQL ) or die ( mysql_error());
        $row = mysql_fetch_array($result);
        return $row['cnt'];
    }
    return 0;
  }
}
?>
