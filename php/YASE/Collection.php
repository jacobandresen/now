<?php
class Collection
{
  public $id; 
  public $parentId;
  public $name;
  public $pageLimit;
  public $levelLimit; 
  public $startUrl;

  public $domains;
  
  public function __construct ( ) 
  {
    $this->domains = array(); 
  } 

  public static function create ($data) 
  {
    $c = new Collection(); 
    $c->parentId = $data->parentId;
    $c->name = $data->name;
  
    $SQL = "INSERT INTO collection(parent_id, name, page_limit, level_limit, start_url) VALUES(".$data->parentId.", '".$data->name."', ".$data->pageLimit.", ".$data->levelLimit.",'".$data->startUrl."')";
    mysql_query($SQL) or die ("collection create failed: $SQL" .mysql_error());
    $c->domains = array();
    $c->id = mysql_insert_id();
    
    return($c); 
  }

  public static function retrieve ($data)
  { 
     if (isset($data->parentId)){
       $SQL = "SELECT id,name,page_limit,level_limit,start_url FROM collection where parent_id=".$data->parentId;
     } else {
       $SQL = "SELECT id,name,page_limit,level_limit,start_url FROM collection where id=".$data->id;
     } 
     $res = mysql_query($SQL) or die("collection read failed:".$SQL." -> ".mysql_error());
  
     $collections = array(); 
     while ($row = mysql_fetch_row($res)) { 
       $c = new Collection();
       $c->id = $row[0];
       $c->name = $row[1]; 
       $c->pageLimit = $row[2];
       $c->levelLImit = $row[3];
       $c->startUrl = $row[4];
       $c->domains = Domain::retrieve( json_decode('{"parentId":"'.$c->id.'"}')); 
      
       array_push($collections, $c); 
     }
     return $collections;
  }

  public static function update ($data)
  {
    mysql_query("UPDATE collection where id=".$data->id." set  parent_id=".$data->parentId.",name='".$data->name."', page_limit='".$data->pageLimit."', level_limit='".$data->levelLimit.")") or die (mysql_error());
  }

  public static function destroy ( $id )
  {
    mysql_query("DELETE FROM collection WHERE ID=$id") or die (mysql_error());
  }

  public function addDomain ( $domain ) 
  { 
    $d = new Domain();
    $d->name  = $domain;
    $d->parentId = $this->id; 
    Domain::create($d);
    $this->domains = Domain::retrieve( json_decode('{"parentId":"'.$this->id.'"}')); 
  }

  public function inAllowedDomains ( $URL )
  { 
    $host = URL::extractHost($URL);
    foreach ($this->domains as $d) {
      $domain = str_replace("www.", "", $d->name);
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

  public function log ( $message ) 
  {
    print $message."\r\n";
  }

}
?>
