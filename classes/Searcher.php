<?php
class Searcher
{
  private $accountId;
  public $limit = 5;

  public function __construct($accountId)
  {
    $this->accountId=$accountId;
  }

  public function search ($query, $page)
  {
    $results = array();
    $index=0;
    $limit = '';
    if ($page != 0){
      $offset = ($page*$this->limit) - $this->limit;
      $limit = " LIMIT " . $this->limit . " OFFSET $offset";
    }

    if($query!=""){
      $result = mysql_query("SELECT *, MATCH(content) AGAINST('$query') AS score FROM document WHERE MATCH(content) AGAINST('$query') and account_id='".$this->accountId."' ORDER BY score DESC ".$limit) or die(mysql_err()); 
      $pos=0;
      while ($row=mysql_fetch_array($result)){
        $pos++;
        $title=$row['title'];
        $content=$row['content'];
        //$content = preg_replace('/\&.*?\;/is',' ', $content);
        print $content; 
        $document = new Document();
        $document->url = urldecode($row['url']);
        $document->title = trim(html_entity_decode($title));
        if($document->title==""){
          $document->title = $document->url;
        }
         //create excerpt
        $document->content = substr($content, 1, 400);
       // $document->sContent =$content;
        $results[$pos] = $document;
      }
    }
    return $results;
  }
}
?>
