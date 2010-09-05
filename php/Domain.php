<?php
class Domain
{
  public $id; 
  public $collectionId; 
  public $name;

  public function __construct () 
  {
  }

  public static function create ($data)
  {
    $d= new Domain();
    $SQL= "INSERT INTO domain(collection_id,name) values(".$data->collectionId.",'".$data->name."')";
    mysql_query($SQL) or die (mysql_error());
    $d->id=mysql_insert_id();
    $d->collectionId=$data->collectionId;
    $d->name = $data->name; 
    return $d; 
  }

  public static function read ($data)
  {
    $res = mysql_query("SELECT id,name FROM domain where id=".$data->id) or die (mysql_error());
    $row = mysql_fetch_row($res); 
    $d = new Domain();
    $d->id = $row[0];
    $d->name = $row[1];

    return $d; 
  }

  public static function update ($data)
  {
    $res = mysql_query("UPDATE domain WHERE id=".$data->id." SET collection_id=".$data->collectionId." and name='".$data->name."'") or die(mysql_error());
  }

  public static function destroy ($data)
  {
    mysql_query("DELETE FROM domain where id=".$data->id);
  }
}
