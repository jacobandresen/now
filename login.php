<?
 require_once("classes/Global.php"); 
 require_once("classes/Yase.php");
 require_once("app/template.php"); 
 require_once("app/views/login.php");


 //perform login and redirect to index page
  if ( User::login ($_REQUEST['username'], $_REQUEST['password']) ){
    $_SESSION['user_id']=User::getId($_REQUEST['username']); 
    $_SESSION['account_id']=User::getFirstAccountId(); 
    $_SESSION['account_domain']=Account::getDomain( $_SESSION['account_id']);
    header ('Location: index.php');
   } else {
     if( isset($_REQUEST['username'])){ 
       $message="login failed"; 
     }
  } 

 //the page to show if the login fails
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

