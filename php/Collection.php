<?php
class Collection
{
  public $id; 
  public $name;
  public $ownerId; 
  public $domains;
  
  public function __construct ( ) 
  {
    $this->domains = array(); 
  } 

  public static function create ($ownerId, $name) 
  {
    $c = new Collection(); 
    $t->ownerId = $ownerId;  
    $t->name = $name;
  
    mysql_query("INSERT INTO collection(owner_id, name) VALUES($ownerId, '$name')") or die (mysql_error());
    $c->domains = array();
    $c->id = mysql_insert_id();
   
    return($c); 
  }
  
  public static function read ($collectionId)
  { 
    $c = new Collection();
    $c->id = $collectionId;
    $res = mysql_query("SELECT owner_id,name FROM collection where id=$collectionId") or die(mysql_error());
    $row = mysql_fetch_row($res);
    if ($row) {
      $c->ownerId = $row[0];
      $c->name = $row[1]; 
      $c->domains = array();
      $c->readDomains();  
    } 
    return ($c);
  }

  public function delete ()
  {
    mysql_query("DELETE FROM collection WHERE ID=$this->id") or die (mysql_error());
  }

  public function addDomain ( $domain ) 
  { 
    mysql_query("INSERT INTO collection_in_domain(collection_id,domain) values('".$this->id."','".$domain."')") or die (mysql_error()); 
    array_push($this->domains, $domain); 
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
