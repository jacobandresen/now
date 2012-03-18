<?php
class Searcher
{
    private $collectionId;

    public function __construct($data)
    {
        $this->collectionId = $data->id;
    }

    public function search($query)
    {
        $results = array();

        if ($query != "") {
            $SQL = "SELECT ts_rank( to_tsvector('english', n.content), to_tsquery('english', '$query')) as rank ";
            $SQL.= ", d.document_id , n1.content as title, ts_headline('english', n.content, to_tsquery('english', '$query')) as content, d.url, d.content_type ";
            $SQL.= " from document d, node n, node n1 where n1.document_id=d.document_id  and d.collection_id = '".$this->collectionId."' ";
            $SQL.= " and n1.name='title' and n.document_id = d.document_id and n.name='content' order by rank desc";

            $res = pg_query($SQL);

            $pos = 0;
            while ($row = pg_fetch_array($res)) {
                $rank = $row['rank'];

                if ($rank > 0.005 ) {
                    $title = $row['title'];
                    $content = $row['content'];
                    $content = substr($content, 1, 400);

                    $result = new Result();
                    $result->id = $pos+1;
                    $result->documentId = $row['document_id'];
                    $result->url = urldecode($row['url']);
                    $result->rank = $rank;
                    $result->title = HTMLRobot::clean(html_entity_decode($title));
                    $result->fragment = HTMLRobot::clean(html_entity_decode($content));
                    $results[$pos] = $result;
                    $pos++;
                }
            }
        }
        return $results;
    }
}
?>
