<?php
class Collection
{
  public $id; 
  public $name;
  public $pageLimit;
  public $levelLimit; 
  public $ownerId;
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

  public static function retrieve ($collectionId)
  { 
    $c = new Collection();
    $c->id = $collectionId;
    $res = mysql_query("SELECT id,owner_id,name,page_limit,level_limit FROM collection where id=$collectionId") or die(mysql_error());
    $row = mysql_fetch_row($res);
    if ($row) {
      $c->id = $row[0];
      $c->ownerId = $row[1];	    
      $c->name = $row[2]; 
      $c->pageLimit = $row[3];
      $c->levelLImit = $row[4];
      $c->domains = array();
      $c->readDomains();  
    } 
    return ($c);
  }

  public static function update ($data)
  {
    mysql_query("UPDATE collection where id=".$data->id." set  owner_id=".$data->ownerId.",name='".$data->name."', page_limit='".$data->pageLimit."', level_limit='".$data->levelLimit.")") or die (mysql_error());
  }

  public static function delete ( $id )
  {
    mysql_query("DELETE FROM collection WHERE ID=$id") or die (mysql_error());
  }

  public function addDomain ( $domain ) 
  { 
    $SQL ="INSERT INTO collection_in_domain(collection_id,domain) values('".$this->id."','".$domain."')";
    mysql_query($SQL) or die(mysql_error()); 
    $this->domains = array ();
    $this->readDomains();
  }

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
  
  private function readDomains ()
  {
    $this->domains = array(); 
    $res = mysql_query("SELECT domain FROM collection_in_domain where collection_id=".$this->id) or die (mysql_error());
    while ( ($row = mysql_fetch_array($res)) )  {
      array_push($this->domains, $row[0]);
    }
  }
}
?>
