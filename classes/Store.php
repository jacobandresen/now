<?php
class Store {
	
  public  $iOwner; //	
  public  $sTable;


  function __construct($iOwner){
     $this->iOwner=$iOwner;
  }


  function Search($sQuery){
  }

  function GetAll(){
  } 
  
  function Get($iID){
   }

  function Put($iID, $sName, $sValue){
  } 

  function Delete($iID){

  }

};


$s=new Store(1);
$s->sTable="crawlskip";


?>
