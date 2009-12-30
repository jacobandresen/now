<?php
require_once("classes/YASE.php");
ini_set("soap.wsdl_cache_enabled", "0");

function search($domain, $query) {
  $y = new YASE();
  return( $y->search($domain, $query) );
}

$server = new SoapServer(null, array(
  'uri' => "urn://searchzen.org/yase",
  'soap_version' => SOAP_1_2));
$server->addFunction("search");
$server->handle();
?>
