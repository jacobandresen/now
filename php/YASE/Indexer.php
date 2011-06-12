<?php
//2011, Jacob Andresen <jacob.andresen@gmail.com>
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
        //TODO: delete fields in current collection 

        $SQL = "select max(retrieved),id,url,content_type,content,level from document where collection_id='" . $this->collection->id . "' group by url";
        $res = mysql_query($SQL) or die (mysql_error());

        while ($row = mysql_fetch_array($res)) {
            $document = new Document();
            $document->id = $row['id'];
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

                //analyze HTML as default
                $document->content = html_entity_decode($document->content, ENT_QUOTES);
                $document->title = HTMLRobot::findTitle($document->content);
                $document->title = htmlentities($document->title, ENT_QUOTES);
                $document->content = HTMLRobot::clean($document->content);
            }

            //default rules
            if ($document->title == "") {
                $document->title = $document->url;
            }

            $md5 = md5($document->content);
            $this->setMD5($document->id, $md5);

            $this->saveFacets($document);

        } catch (Exception $e) {
            $this->collection->log("failed adding $document->url " . $e->getMessage());
        }
    }

    protected function saveFields($document)
    {
        $length = strlen($document->content);
        $this->collection->log("[" . $length . "]INDEX " . urldecode($document->url) . " type:" . $document->contentType);

        if ($length > 0 && strlen($document->url) > 0) {

            $SQL = "INSERT INTO field(document_id,name,content) values('" . $document->id . "','title','" . $document->title . "');";
            mysql_query($SQL) or die ("failed to insert title field:" . mysql_error());

            $SQL = "INSERT INTO field(document_id,name,content) values('" . $document->id . "','content','" . $document->content . "');";
            mysql_query($SQL) or die ("failed to insert content field: " . mysql_error());

        } else {
            $this->collection->log($document->url . " empty doc");
        }
    }

    private function setMD5($id, $md5)
    {
        $SQL = "update document where id='" . $id . "' set md5='" . $md5 . "'";
        mysql_query($SQL);
    }
}

?>
