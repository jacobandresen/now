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
     $SQL = "SELECT distinct(d.url) as url ,d.contenttype as contenttype ,t.content as title, c.content as content, MATCH(c.content) AGAINST('$query') as score from account a,document d,facet t, facet c where a.id='".$this->accountId."' and d.id=t.document_id and t.name='title' and  d.id=c.document_id and c.name='content' order by score desc";
     $result = mysql_query($SQL) or die("search failed:".mysql_error());

     $pos=0;
      while ($row=mysql_fetch_array($result)){
        $title=$row['title'];
        $content=$row['content'];
	      $content=iconv("UTF-8", "ISO-8859-1", $content);
        $content=substr($content,1,400);
        $document = new Document();
        $document->url = urldecode($row['url']);
        $document->title = trim(html_entity_decode($title));
        $document->content = htmlentities($content);
        $document->contentType = $row['contenttype'];
        $pos++;
        $results[$pos] = $document;
      }
    }
    return $results;
  }
}
?>
