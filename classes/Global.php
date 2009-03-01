<?php

//////////////////////////////////////////////////
//
// connect to database
//
//////////////////////////////////////////////////

mysql_connect("localhost", "jacob", "jakb0531") or die(mysql_error());
mysql_select_db("search") or die(mysql_error());
session_start();

//////////////////////////////////////////////////
//
// constants
//
//////////////////////////////////////////////////

define("DEBUG", true);
//defhttp://localhost/phpmyadmin/ine("REGISTRATION_INCOMPLETE", -10);

//define("BASE_URL", "http://www.johanbackstrom.se/");

$aTinyMCE = array();

?>
