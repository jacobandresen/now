<?php

include('Field.php');

class Store {
	
  protected  $iOwner; 	
  protected  $sTable;

  function __construct($iOwner,$sTable){
	  $this->iOwner=$iOwner;
	  $this->sTable=$sTable;
  }

  function getAll(){
    $results=array();  
    $res = mysql_query("select id,name,value from store where storename='".$this->sTable."' and owner='".$this->iOwner."'") or  die (mysql_error());
    while ($row = mysql_fetch_array($res) ){
	    $f=new Field();
	    $f->name= $row['name'];
	    $f->value= $row['value'];
            array_push($results, $f);	
	   }
    return($results);
  } 
  
  function get($iID){
   $f=new Field();
   $res = mysql_query("select id,name,value from ".$this->sTable." where storename='".$this->sTable." and owner='".$this->iOwnerId."'");
    if($row=mysql_fetch_array($res)){
       $f 		= new Field();
       $f->name 	= $row['name'];
       $f->value 	= $row['value'];
    }
    return($f); 
   }

  function put($sName, $sValue ){
	  mysql_query("INSERT INTO store(storename,owner,name,value) value('".$this->sTable."','".$this->iOwner."','".$sName."','".$sValue."')") or die (mysql_error());
  
  } 

  function delete($iID){
	mysql_query("DELETE FROM store where storename='".$this->sTable."' and id='".$iID) or die(mysql_error());
  }

  function deleteAll(){
	mysql_query("DELETE FROM store where storename='".$this->sTable."' and owner='".$this->iOwner."'") or die(mysql_error());
  }
};


?>
