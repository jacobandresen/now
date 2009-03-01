<?php

//////////////////////////////////////////////////
//
// connect to database
//
//////////////////////////////////////////////////

mysql_connect("localhost", "yase", "yase") or die(mysql_error());
mysql_select_db("yase") or die(mysql_error());
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
