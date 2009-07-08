<?
 require_once("classes/Global.php"); 
 require_once("classes/User.php"); 
 require_once("app/template.php"); 
 require_once("app/views/login.php");

 //check user login
  if ( User::login ($_REQUEST['username'], $_REQUEST['password']) ){
    session_start(); 
    $_SESSION['account_id']=$_REQUEST['username']; 
     header ('Location: index.php');
   } else {
     if( isset($_REQUEST['username'])){ 
       $message="login failed"; 
     }
  } 

 head("Yet another search engine");

 leftbar();
 loginForm();

 ?>
 <p>
 <?php
 print $message;
 ?>
 </p>
 <?php 
 bottom();
?>

