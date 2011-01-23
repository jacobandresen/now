<?php
//2011, Jacob Andresen <jacob.andresen@gmail.com>
class Domain
{
  public $id; 
  public $parentId; 
  public $name;

  public function __construct () 
  {
  }

  public static function create ($data)
  {
    $d= new Domain();
    $SQL= "INSERT INTO domain(parent_id,name) values(".$data->parentId.",'".$data->name."')";
    mysql_query($SQL) or die (mysql_error());
    $d->id=mysql_insert_id();
    $d->parentId=$data->parentId;
    $d->name = $data->name; 
    return $d; 
  }

  public static function retrieve ($data)
  {
    if (isset($data->id)) { 
     $res = mysql_query("SELECT id,name FROM domain where id=".$data->id) or die (mysql_error());
    } else {
     $res = mysql_query("SELECT id,name from domain where id=".$data->parentId) or die (mysql_error());
    } 

    $domains = array();
    while ( $row = mysql_fetch_row($res) ){
      $d = new Domain();
      $d->id = $row[0];
      $d->name = $row[1];
      array_push($domains, $d); 
    }
    return $domains; 
  }

  public static function update ($data)
  {
    $res = mysql_query("UPDATE domain WHERE id=".$data->id." SET parent_id=".$data->parentId." and name='".$data->name."'") or die(mysql_error());
  }

  public static function destroy ($data)
  {
    mysql_query("DELETE FROM domain where id=".$data->id);
  }

}
