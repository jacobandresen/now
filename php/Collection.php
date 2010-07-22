<?php
class Collection
{
  public $id; 
  public $name;
  public $ownerId; 
  public $domains;
  
  public function __construct ($ownerId, $name) 
  {
    $this->ownerId = $ownerId;  
    $this->name = $name;
  
    mysql_query("INSERT INTO collection(owner_id, name) VALUES($ownerId, '$name')") or die (mysql_error());
 
    $this->id = mysql_insert_id();
  }

  public function read ($collectionId)
  {
    $this->id = $collectionId;
    $res = mysql_query("SELECT owner_id,name FROM collection where id=$collectionId") or die(mysql_error());
    $row = mysql_fetch_row($res);
    
    $this->ownerId = $row[0];
    $this->name = $row[1]; 

    $this->readDomains();  
    return ($this);
  }

  private function readDomains ()
  {
    $res = mysql_query("SELECT domain FROM collection_in_domain where collection_id=".$this->id) or die (mysql_error());
    while ( ($row = mysql_fetch_array($res)) )  {
     print $row[0];
     array_push($this->domains, $row[0]);
    }
  }

  public function delete ()
  {
    mysql_query("DELETE FROM collection WHERE ID=$this->id") or die (mysql_error());
  }

  public function addDomain ( $domain ) 
  {
    mysql_query("INSERT INTO collection_in_domain(collection_id,domain) values (".$this->id.",'$domain')") or die (mysql_error()); 
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
}
?>
