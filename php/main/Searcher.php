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

            $SQL = "SELECT ts_rank( to_tsvector('english', n.content), to_tsquery('english', '$query')) ";
            $SQL.= ",  d.content, d.url, d.content_type, (select n1.content from node n1 where n1.name='title' and n1.document_id = d.document_id) as title  ";
            $SQL.= " from document d, node n where n.document_id = d.document_id and n.name='content'";

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
