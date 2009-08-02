<?php
 require_once("classes/HTTPClient.php"); 
 require_once("classes/Ting/Client.php");
 require_once("app/template.php");
 
 Template::head("ting search");

 $query = $_REQUEST['query'];
 $t=new Ting_Client();
?>

<form action="ting.php" method="get">
 <input type="text" name="query"/>
 <input type="submit" name="search" value="search"/>
</form>

<?php
 $searchResponse = json_decode($t->search($query));
 if($searchResponse->searchResult->hitCount>0) {
 print "<ul>\r\n";
 foreach( $searchResponse->searchResult->records->tingRecord 
           as $rec){
    $isbn=$rec->dc->identifier[0];
    $isbn=preg_replace("/ISBN\:/","",$isbn);
    print "<li><a href=\"http://slashdemocracy.org/isbn/".$isbn."\">".$rec->dc->title[0]."</a></li>"; 
  }
 }

 Template::bottom();
?>


