<?php
class Collection
{
    public $id;
    public $parentId;
    public $name;
    public $pageLimit;
    public $levelLimit;
    public $startUrl;


    public function __construct()
    {
    }

    public function create($data)
    {
      parent::create($data);
      
      if (issset($data->domains)) {
          foreach ($data->domains as $d) {
              $domain = new Domain();
              $domain->create($d);
          }
      }
    }

    public function retrieve($data)
    {
        parent::retrieve($data);
        parent::associate("Domain", "domains");
    }

    public function update($data)
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
        $d->create($d);

        $this->associate("Domain", "domains");
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

}
?>
