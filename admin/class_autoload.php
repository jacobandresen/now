<?
require_once("global.php");
require_once("../classes/Template.php");
require_once("../classes/YASE/Framework.php");

session_start();

function __autoload($sClassName) { // php 5
	require_once "classes/$sClassName.php";
}
?>
