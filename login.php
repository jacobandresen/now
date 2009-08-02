<?
 require_once("YASE/Framework.php");
 require_once("app/template.php"); 
 
 //perform login and redirect to index page
  if ( User::login ($_REQUEST['username'], $_REQUEST['password']) ){
    header ('Location: index.php');
   } else {
     if( isset($_REQUEST['username'])){ 
       $message="login failed"; 
     }
  } 
 require_once("app/views/login.php");

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
