<?php
class Searcher
{
  private $iAccountId;
  public $iLimit = 5;

  public function __construct($iAccountId)
  {
    $this->iAccountId=$iAccountId;
  }

  public function search ($query, $iPage)
  {
    $aRet = array();
    $index=0;
    $sLimit = '';
    if ($iPage != 0){
      $iOffset = ($iPage*$this->iLimit) - $this->iLimit;
      $sLimit = " LIMIT " . $this->iLimit . " OFFSET $iOffset";
    }

    if($query!=""){
      $result = mysql_query("SELECT *, MATCH(content) AGAINST('$query') AS score FROM document WHERE MATCH(content) AGAINST('$query') and account_id='".$this->iAccountId."' ORDER BY score DESC ".$sLimit); 
      $pos=0;
      while ($row=mysql_fetch_array($result)){
        $pos++;
        $title=$row['title'];
        $content=$row['content'];
        $content = preg_replace('/\&.*?\;/is',' ', $content);
        $document = new Document();
        $document->sUrl = urldecode($row['url']);
        $document->sTitle = trim(html_entity_decode($title));
        if($document->sTitle==""){
          $document->sTitle = $document->sUrl;
        }
         //create excerpt
       // $document->sContent = substr($content, 1, 400);
        $document->sContent =$content;
        $aRet[$pos] = $document;
      }
    }
    return $aRet;
  }
}
?>
