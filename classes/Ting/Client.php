<?php
 class Ting_Client {
   var $opensearch = "http://didicas.dbc.dk/opensearch/";

   public function search($query){
     $hc=new HTTPClient();
     $url = $this->opensearch."?action=searchRequest&query=$query&facets.number=10&outputType=json";
     $host = $hc->extractHost($url);
     $hc->connect($host);
     return( $hc->Get($url) ); 
   }
 }

?>
