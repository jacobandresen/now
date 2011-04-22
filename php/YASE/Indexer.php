<?php
class Indexer
{
    public $collection;

    public function __construct($ownerId)
    {
        $account = new Account();
        $account->retrieve(json_encode("{'id':".$ownerid)); 
        $this->collection = $account->collections[0];
    }

    public function start()
    {
        $SQL = "DELETE FROM facet where parent_id='" . $this->collection->id . "'";
        mysql_query($SQL) or die (mysql_error());

        $SQL = "select max(retrieved),id,url,content_type,content,level from document where parent_id='" . $this->collection->id . "' group by url";
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

                $document->content = html_entity_decode($document->content, ENT_QUOTES);
                $document->title = HTMLRobot::findTitle($this->collection->parentId, $document->content);
                $document->title = htmlentities($document->title, ENT_QUOTES);
                $document->content = HTMLRobot::clean($document->content);
            }

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

    protected function saveFacets($document)
    {
        $length = strlen($document->content);
        $this->collection->log("[" . $length . "]INDEX " . urldecode($document->url) . " type:" . $document->contentType);

        if ($length > 0 && strlen($document->url) > 0) {

            $SQL = "INSERT INTO facet(parent_id,name,content) values('" . $document->id . "','title','" . $document->title . "');";
            mysql_query($SQL) or die ("failed to insert title facet:" . mysql_error());

            $SQL = "INSERT INTO facet(parent_id,name,content) values('" . $document->id . "','content','" . $document->content . "');";
            mysql_query($SQL) or die ("failed to insert content facet: " . mysql_error());

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
