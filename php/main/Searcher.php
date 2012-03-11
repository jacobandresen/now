<?php
class Searcher
{
    private $collectionId;
    public $limit = 5;

    public function __construct($data)
    {
        $this->collectionId = $data->id;
    }

    public function search($query, $page)
    {
        $results = array();
        $index = 0;
        $limit = '';
        if ($page != 0) {
            $offset = ($page * $this->limit) - $this->limit;
            $limit = " LIMIT " . $this->limit . " OFFSET $offset";
        }

        if ($query != "") {
            $SQL = "SELECT distinct(d.url) as url,d.content_type as content_type ,t.content as title, c.content as content, MATCH(c.content) AGAINST('$query') as score from document d,field t, field c where d.collection_id='" . $this->collectionId . "' and d.id=t.document_id and t.name='title' and  d.id=c.document_id and c.name='content' order by score desc";
            $result = pg_query($SQL);

            $pos = 0;
            while ($row = pg_fetch_array($result)) {
                $title = $row['title'];
                $content = $row['content'];
                $content = substr($content, 1, 400);
                $document = new Document();
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
