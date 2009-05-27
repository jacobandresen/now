<?php
include('Field.php');

class Setting {
  protected  $iOwner; 	
  protected  $sTable;

  function __construct($iOwner,$sTable){
    $this->iOwner=$iOwner;
    $this->sTable=$sTable;
  }

  function put($sName, $sValue, $sType ){
     mysql_query("INSERT INTO setting(tablename,account_id,name,value,type) value('".$this->sTable."','".$this->iOwner."','".$sName."','".$sValue."','".$sType."')") or die (mysql_error());
  
  } 
  
  function getAll(){

    $results=array();  
    $res = mysql_query("select id,name,value,type from setting where tablename='".$this->sTable."' and account_id='".$this->iOwner."'") or  die (mysql_error());
    while ($row = mysql_fetch_array($res) ){
      $f=new Field();
      $f->name= $row['name'];
      $f->value= $row['value'];
      $f->type= $row['type'];
      array_push($results, $f);	
    }
    return($results);
  } 
  
  function get($sName){
    $f=new Field();
    $res = mysql_query("select id,name,value,type from setting where tablename='".$this->sTable."' and account_idr='".$this->iOwnerId."' and name='".$this->sName."'");
    if($row=mysql_fetch_array($res)){
      $f 	  = new Field();
      $f->name 	 = $row['name'];
      $f->value  = $row['value'];
      $f->type   = $row['type']; 
    }
    return($f); 
  }

 
  function delete($iID){
     mysql_query("DELETE FROM setting where tablename='".$this->sTable."' and id='".$iID) or die(mysql_error());
  }
 
  function deleteAll(){
     mysql_query("DELETE FROM setting where tablename='".$this->sTable."' and account_id='".$this->iOwner."'") or die(mysql_error());
  }
};
?>
