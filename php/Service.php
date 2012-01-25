<?php
class Crawler
{
    public $collection;
    public $level;
    public $processURLs;
    public $seenURLs;
    public $crawledURLs;
    public $httpClient;

    public function __construct($params)
    {
        $this->collections = collection::retrieve($params);
        $this->collection = $this->collections[0];

        if (!isset($this->collection)) {
            
            throw new Exception("failed to find collection for");
        }

        if (isset($params->pageLimit)) {
            $this->pageLimit = $params->pageLimit;
        } else {
            $this->pageLimit = 1500;
        }
        $this->processURLs = array();
        $this->seenURLS = array();
        $this->foundURLs = array();
        $this->crawledURLs = array();
        $this->startUrl = $this->collection->startUrl;

        $this->httpClient = new HTTPClient();
    }
  
    public function start()
    {
        $this->collection->log("page limit:" . $this->pageLimit);
        if ($this->shouldCrawl($this->startUrl)) {
            mysql_query("delete from document where coolection_id='" . $this->collection->id . "'");
            $this->crawl($this->startUrl, 0, $this->startUrl);
        } else {
            $this->collection->log("failed to start crawl");
        }
    }

    public function crawl($url, $level, $parent)
    {
        if (count($this->crawledURLs) > $this->pageLimit) {
            return;
        }

        $this->collection->log("crawl - [level:$level] [page:" . count($this->crawledURLs) . "]  - $url ");

        $document = $this->httpClient->getDocument($url);
        $document->level = $level;

        if (!($this->shouldCrawl($url))) {
            $this->collection->log("SKIP $url");
            array_push($this->foundURLs, $url); //skip document
            return false;
        }

        preg_match_all("/\<a.*?(?:src|href)=\"([^\"]*?)\"/i",
                       $document->content, $matches);
        foreach ($matches[1] as $item) {
            $fullUrl = URL::expandUrl($item, $url);
            if ($this->shouldCrawl($fullUrl)) {
                $link = new Document();
                $link->url = $fullUrl;
                $link->level = $level + 1;
                array_push($this->foundURLs, $link);
                array_push($this->processURLs, $link);
            }
         }

         $document->content = htmlentities($document->content, ENT_QUOTES);
         $document->save($this->collection->id);
         array_push($this->crawledURLs, $url);
         if (count($this->crawledURLs) > $this->pageLimit) {
             $this->collection->log("hit page limit!");
             $this->collection->log("#crawledURLs:" . count($this->crawledURLs));
             return;
         }

         while ($child = array_shift($this->processURLs)) {
             if ($child->url != "") {
                 if (!in_array($child->url, ($this->crawledURLs))) {
                     $this->crawl($child->url, ($child->level), $url);
                 }
             }
         }
    }

    private function shouldCrawl($url)
    {
        if (in_array($url, $this->crawledURLs)) {
            return false;
        }
        if ($this->collection->inAllowedDomains($url) == false) {
            return false;
        }

        if ($this->level > $this->collection->levelLimit ||
            count($this->crawledURLs) > $this->collection->pageLimit ||
            URL::filter($this->collection->getDomainId($url), $url, "crawlerfilter")) {
            return false;
        }
        return true;
    }

   private function crawleable($contentType) 
    {
       if (
            ($contentType == "application/x-zip") ||
            ($contentType == "application/xml") ||
            ($contentType == "application/json") ||
            ($contentType == "image/jpeg") ||
            ($contentType == "image/jpg") ||
            ($contentType == "image/bmp") ||
            ($contentType == "text/css") ||
            ($contentType == "text/javascript") 
          ) {
            return false;
      }
      return true;
    }
};


class Indexer
{
    public $collection;

    public function __construct($params)
    {
        $this->collections = collection::retrieve($params);
        $this->collection = $this->collections[0];
    }

    public function start()
    {
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

            $this->saveFields($document);

        } catch (Exception $e) {
            $this->collection->log("failed adding $document->url " . $e->getMessage());
        }
    }

    protected function saveFacets($document)
    {
        $length = strlen($document->content);
        $this->collection->log("[" . $length . "]INDEX " . urldecode($document->url) . " type:" . $document->contentType);

        if ($length > 0 && strlen($document->url) > 0) {

            $SQL = "INSERT INTO facet(document_id,name,content) values('" . $document->id . "','title','" . $document->title . "');";
            mysql_query($SQL) or die ("failed to insert title field:" . mysql_error());

            $SQL = "INSERT INTO facet(document_id,name,content) values('" . $document->id . "','content','" . $document->content . "');";
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
