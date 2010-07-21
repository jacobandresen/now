<?php
class Collection
{
  public $id; 
  protected $accountId;
  protected $name;
  protected $domains;
  
 // protected $seenNumberOfPages;
 // protected $crawledNumberOfPages;
 // protected $indexedNumberOfPages;

  public function __construct ($accountId, $name) 
  {
    $this->accountId = $accountId;
    $this->name = $name;
  
    mysql_query("INSERT INTO collection(owner_id, name) VALUES($accountId, '$name')") or die (mysql_error());
 
    $this->id = mysql_insert_id();
  }

  public function make ($collectionId)
  {
    $this->id = $colletionId;
    $res = mysql_query("SELECT account_id,name FROM account where id=$collectionId");
    $row = mysql_fetch_row($res);
    $this->accountId = $row['account_id'];
    $this->name = $row['name']; 

    $res = mysql_query("SELECT domain  FROM collection_in_domain where collection_id=$collectionId") or die (mysql_error());
    while ($row = mysql_fetch_row($res) )  {
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
