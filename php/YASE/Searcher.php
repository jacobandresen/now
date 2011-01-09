<?php
class Searcher
{
  private $parentId;
  public $limit = 5;

  public function __construct($data)
  {
    $this->parentId = $data->id;
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
     $SQL = "SELECT distinct(d.url) as url ,d.content_type as content_type ,t.content as title, c.content as content, MATCH(c.content) AGAINST('$query') as score from document d,facet t, facet c where d.parent_id='".$this->parentId."' and d.id=t.parent_id and t.name='title' and  d.id=c.parent_id and c.name='content' order by score desc";
     
     $result = mysql_query($SQL) or die("search failed:".mysql_error());

     $pos=0;
     while ($row=mysql_fetch_array($result)){
       $title         =$row['title'];
       $content       =$row['content'];
       $content       =substr($content,1,400);
       $document      = new Document();
       $document->url = urldecode($row['url']);
       $document->title = trim(html_entity_decode($title));
       $document->content = htmlentities($content);
       $document->contentType = $row['content_type'];
       $pos++;
       $results[$pos] = $document;
    }
   }
   return $results;
  }
}
?>
