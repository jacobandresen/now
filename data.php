<?php
 require_once("classes/Global.php");
 require_once("classes/Search/Framework.php");
 $y=new Yas();
 $y->ticketLogin("1234567890"); 
 if($y->loggedIn()){
  $y->setup();
  print "{[";
  foreach( $y->getIndexSkip() as $filter ) {
    print "'".$filter."',";
   }
  print "]}";
}
?>
