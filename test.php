<?php
$client = new SoapClient(null, array(
  'location' => "http://searchzen.org/jacob/yase/YASE.php",
  'uri' => "http://searchzen.org/yase",
  'trace' => 1));
try{
 $return = $client->search("www.pedant.dk", "javascript");
 print_r($return);
}catch(SoapFault $exception) {
  print $client->__getLastResponse();
}
?>
