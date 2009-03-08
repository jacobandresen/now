<?php

include('Field.php');

class Store {
	
  protected  $iOwner; 	
  protected  $sTable;

  function __construct($iOwner,$sTable){
	  $this->iOwner=$iOwner;
	  $this->sTable=$sTable;
  }

  function GetAll(){
    $results=array();  
    $res = mysql_query("select id,name,value from ".$this->sTable." where owner='".$this->iOwner."'") or  die (mysql_error());
    while ($row = mysql_fetch_array($res) ){
	    $f=new Field();
	    $f->name= $row['name'];
	    $f->value= $row['value'];
            array_push($results, $f);	
	   }
    return($results);
  } 
  
  function Get($iID){
   $f=new Field();
   $res = mysql_query("select id,name,value from ".$this->sTable." where owner='".$this->iOwnerId."'");
    if($row=mysql_fetch_array($res)){

       $f 		= new Field();
       $f->name 	= $row['name'];
       $f->value 	= $row['value'];
       $f->type 	= $row['type']; 
    }
    return($f); 
   }

  function Put($sName, $sValue ){
	  mysql_query("INSERT INTO ".$this->sTable."(owner,name,value) value('".$this->iOwner."','".$sName."','".$sValue."')") or die (mysql_error());
  
  } 

  function Delete($iID){
	mysql_query("DELETE FROM ".$this->sTable." where id='".$iID) or die(mysql_error());
  }

  function DeleteAll(){
	mysql_query("DELETE FROM ".$this->sTable." where owner='".$this->iOwner."'") or die(mysql_error());
  }
};


?>
