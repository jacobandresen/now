<?php
 require_once("classes/Global.php"); 
 require_once("classes/Framework.php");

 $query=$_REQUEST['query'];
 
 $y=new Yase("pedant.dk");
 if($query!=""){ 
   $y->search($query);
 }

?>
