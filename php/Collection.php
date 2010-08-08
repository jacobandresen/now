<?php
class Collection
{
  public $id; 
  public $ownerId;
  public $name;
  public $pageLimit;
  public $levelLimit; 
  public $startUrl;

  //details
  public $domains;
  
  public function __construct ( ) 
  {
    $this->domains = array(); 
  } 

  public function log ($message) 
  {
     print $message."\r\n"; 
  }

  public static function create ($data) 
  {
    $c = new Collection(); 
    $c->ownerId = $data->ownerId;
    $c->name = $data->name;
  
    $SQL = "INSERT INTO collection(owner_id, name, page_limit, level_limit, start_url) VALUES(".$data->ownerId.", '".$data->name."', ".$data->pageLimit.", ".$data->levelLimit.",'".$data->startUrl."')";
    mysql_query($SQL) or die ("collection create failed: $SQL" .mysql_error());
    $c->domains = array();
    $c->id = mysql_insert_id();
    
    return($c); 
  }

  public static function read ($data)
  { 
     $SQL = "SELECT id,owner_id,name,page_limit,level_limit,start_url FROM collection where id=".$data->id;
     $res = mysql_query($SQL) or die("collection read failed:".$SQL." -> ".mysql_error());
   
     if ($row = mysql_fetch_row($res)) { ;
       $c = new Collection();
       $c->id = $row[0];
       $c->ownerId = $row[1];	    
       $c->name = $row[2]; 
       $c->pageLimit = $row[3];
       $c->levelLImit = $row[4];
       $c->startUrl = $row[5];
       $c->retrieveDomains();  
       return ($c);
     }
  }

  public static function update ($data)
  {
    mysql_query("UPDATE collection where id=".$data->id." set  owner_id=".$data->ownerId.",name='".$data->name."', page_limit='".$data->pageLimit."', level_limit='".$data->levelLimit.")") or die (mysql_error());
  }

  public static function destroy ( $id )
  {
    mysql_query("DELETE FROM collection WHERE ID=$id") or die (mysql_error());
  }

  public function addDomain ( $domain ) 
  { 
    $d = new Domain();
    $d->name  = $domain;
    $d->collectionId = $this->id; 
    Domain::create($d);
    $this->retrieveDomains();
  }

  private function retrieveDomains ()
  {
    $domainIDs = array(); 
    $this->collection->domains = array();
    $res = mysql_query("SELECT id FROM domain where collection_id=".$this->id) or die (mysql_error());
    while ( ($row = mysql_fetch_array($res)) )  {
      array_push($domainIDs, $row[0]);
    }

    foreach($domainIDs as $d){
       array_push($this->collection->domains, Domain::read( (object) array("id"=>$d)));  
    }
  }

  //special commands

  public function inAllowedDomains ( $URL )
  { 
    $host = URL::extractHost($URL);
    foreach ($this->collection->domains as $d) {
      $domain = str_replace("www.", "", $d->name);
      print "DOMAIN:".$domain." ? ".$URL.":".strpos($host,$domain)."\r\n"; 
      if (strpos($host, $domain)!== false) {
        return true;
      } 
    } 
   return false;    
  }

  public function getDomainId ( $url ) 
  {
     foreach ($this->domains as $domain )
     {
	if (URL::inDomain($url , $domain->name) )
        {
	  return ($domain->id);
        }
     }
  }

}
?>
