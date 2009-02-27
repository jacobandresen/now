<?php
 require_once("classes/Global.php");
 require_once("classes/Search/Framework.php");
 $y=new Yas();
 $y->ticketLogin("1234567890"); 
 
 if($y->loggedIn()){
  switch($y->sName){
  case "oCrawlSkip": 
    print "[";
     foreach( $y->getCrawlSkip() as $filter ) {
      print "\"".$filter."\",";
      }
     print "\"\"]";
    break;
  case "oIndexSkip":
    print "[";
     foreach( $y->getIndexSkip() as $filter ) {
      print "\"".$filter."\",";
      }
     print "\"\"]";
    break;
  } 
 }else{
   print "error:'not logged in'";
  }
?>
