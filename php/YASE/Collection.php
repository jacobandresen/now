<?php
//2011, Jacob Andresen <jacob.andresen@gmail.com>
class Collection
{
    public $id;
    public $parentId;
    public $name;
    public $pageLimit;
    public $levelLimit;
    public $startUrl;                       b

    public $domains;

    public function __construct()
    {
    }

    public static function create($data)
    {
        parent::create($data);
    }

    public static function retrieve($data)
    {
        parent::retrieve($data);
    }

    public static function update($data)
    {
        parent::update($data);
    }

    public static function destroy($id)
    {
        parent::destroy($id);
    }

    public function addDomain($domain)
    {
        $d = new Domain();
        $d->name = $domain;
        $d->parentId = $this->id;
        Domain::create($d);
        $this->domains = Domain::retrieve(json_decode('{"parentId":"' . $this->id . '"}'));
    }

    public function inAllowedDomains($URL)
    {
        $host = URL::extractHost($URL);
        foreach ($this->domains as $d) {
            $domain = str_replace("www.", "", $d->name);
            if (strpos($host, $domain) !== false) {
                return true;
            }
        }
        return false;
    }

    public function getDomainId($url)
    {
        foreach ($this->domains as $domain)
        {
            if (URL::inDomain($url, $domain->name)) {
                return ($domain->id);
            }
        }
    }

    public function log($message)
    {
        print $message . "\r\n";
    }
}
?>
