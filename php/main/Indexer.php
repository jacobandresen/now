<?php
class Indexer
{
    public $collection;

    public function __construct($params)
    {
        $this->collections = Collection::retrieve($params);
        $this->collection = $this->collections[0];
    }

    public function start()
    {
        $SQL = "select max(retrieved),document_id,url,content_type,content,level from document where collection_id='" . $this->collection->id . "' group by document_id,url,content_type,level,content";
        $res = pg_query($SQL);

        while ($row = pg_fetch_array($res)) {
            $document = new Document();
            $document->id = $row['document_id'];
            $document->url = $row['url'];
            $document->contentType = $row['content_type'];
            $document->content = $row['content'];
            $document->level = $row['level'];

            $this->analyze($document);
        }
    }

    protected function analyze($document)
    {
        try {
            $title = "";

            if ($document->contentType != "application/pdf") {
                $document->content = html_entity_decode($document->content, ENT_QUOTES);
                $document->title = HTMLRobot::findTitle($document->content);
                $document->title = htmlentities($document->title, ENT_QUOTES);
                $document->content = HTMLRobot::clean($document->content);
            }

            if ($document->title == "") {
                $document->title = $document->url;
            }

            $md5 = md5($document->content);
            $this->setMD5($document->id, $md5);

            $this->saveNodes($document);

        } catch (Exception $e) {
            $this->collection->log("failed adding $document->url " . $e->getMessage());
        }
    }

    protected function saveNodes($document)
    {
        $length = strlen($document->content);
        $this->collection->log("[" . $length . "]INDEX " . urldecode($document->url) . " type:" . $document->contentType);

        if ($length > 0 && strlen($document->url) > 0) {

            if (!Encoding::isUTF8($document->title)) {
                $document->title = iconv("ISO-8859-1", "UTF-8", $document->title);
            }

            if (!Encoding::isUTF8($document->content)){
                $document->content = iconv("ISO-8859-1", "UTF-8", $document->content);
            }

            $SQL = "INSERT INTO node(document_id,name,content) values('". $document->id . "','title','" . $document->title . "');";
            pg_query($SQL);

            $SQL = "INSERT INTO node(document_id,name,content) values('". $document->id . "','content','" . $document->content . "');";
            pg_query($SQL);

        } else {
            $this->collection->log($document->url . " empty doc");
        }
    }

    private function setMD5($id, $md5)
    {
        $SQL = "update document set md5='" . $md5 . "' where document_id='$id'";
        pg_query($SQL);
    }
}
?>
