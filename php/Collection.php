<?php
class Collection
{
 	
  public $id; 
  public $ownerId;
  public $name;
  public $pageLimit;
  public $levelLimit; 

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
  
    mysql_query("INSERT INTO collection(owner_id, name, page_limit, level_limit) VALUES(".$data->ownerId.", '".$data->name."', ".$data->pageLimit.", ".$data->levelLimit.")") or die (mysql_error());
    $c->domains = array();
    $c->id = mysql_insert_id();
    
    return($c); 
  }

  public static function retrieve ($id)
  { 
    $c = new Collection();
    $c->id = $id;
    $res = mysql_query("SELECT id,owner_id,name,page_limit,level_limit FROM collection where id=$id") or die(mysql_error());
    $row = mysql_fetch_row($res);
    if ($row) {
      $c->id = $row[0];
      $c->ownerId = $row[1];	    
      $c->name = $row[2]; 
      $c->pageLimit = $row[3];
      $c->levelLImit = $row[4];

      //details
      $c->retrieveDomains();  
    } 
    return ($c);
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
    $this->domains = array();
    $res = mysql_query("SELECT id FROM domain where collection_id=".$this->id) or die (mysql_error());
    while ( ($row = mysql_fetch_array($res)) )  {
      array_push($domainIDs, $row[0]);
    }

    foreach($domainIDs as $d){
      array_push($this->domains, Domain::retrieve($d));  
    }
  }

  //special commands

  public function inAllowedDomains ( $URL )
  { 
    foreach ($this->domains as $domainStr) {
      $host = URL::extractHost($URL);
      $domain = str_replace("www.", "", $domainStr);
      if (strpos($host, $domain) == false ) {
        return false;
      } 
    } 
   return true;    
  }

  private function getDomainId ( $url ) 
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
