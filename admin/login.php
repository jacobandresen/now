<?
 require_once("../classes/YASE/Framework.php");
 require_once("../classes/Template.php"); 
 require_once("app/views/login.php");
 
 //perform login and redirect to index page
  if ( YASE_User::login ($_REQUEST['username'], $_REQUEST['password']) ){
    header ('Location: index.php');
   } else {
     if( isset($_REQUEST['username'])){ 
       $message="login failed"; 
     }
  } 

 //the page to show if the login fails
 Template::head();
 Template::leftbar();
 
 loginForm();

 ?>
 <p>
 <?php
 print $message;
 ?>
 </p>
 <?php 
 Template::bottom();
?>
