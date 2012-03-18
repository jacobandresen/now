<?php
class Document
{
    public $id;
    public $collectionId;
    public $level;
    public $url;
    public $title;
    public $contentType;
    public $content;

    public function __construct()
    {
    }

    public function save($collectionId)
    {
        if ($collectionId == "") {
            die ("coding error. trying to save to empty collection\r\n");
        }

        if (strlen($this->url) > 1028) {
            return false;
        }
        if (strlen($this->content) > MAX_CONTENT_LENGTH) {
            return false;
        }
        if (strlen($this->content) < 1) {
            return false;
        }

        //HACK: let's pretend it is ISO-8859-1 if it is not UTF-8
        if (!Encoding::isUTF8($this->content)) {
            $this->content = iconv("ISO-8859-1", "UTF-8", $this->content);
        }

        $this->url = urlencode($this->url);
        $SQL = "INSERT into document(collection_id, url, content_type, content, level) values('" . $collectionId . "','" . $this->url . "','" . $this->contentType . "','" . $this->content . "','" . $this->level . "')";
        pg_query($SQL) or die("SQL error:" . $SQL);
        return true;
    }

    public function shouldCrawl()
    {
        if (
            ($this->contentType == "application/x-zip") ||
            ($this->contentType == "application/xml") ||
            ($this->contentType == "application/json") ||
            ($this->contentType == "image/jpeg") ||
            ($this->contentType == "image/jpg") ||
            ($this->contentType == "image/bmp") ||
            ($this->contentType == "image/png") ||
            ($this->contentType == "text/css") ||
            ($this->contentType == "text/javascript")
        ) {
            return false;
        }
        return true;
    }
};
?>
