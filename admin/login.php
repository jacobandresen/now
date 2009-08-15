<?
require_once("class_autoload.php"); 
 
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
?>
<!-- login form --> 
<form class="login" action="login.php" method="POST">
    <div>name </div>
    <input type="text" name="username"></input>
    <div>password </div>
    <div> 
     <input type="password" name="password"></input>
    </div> 
    <input type="submit" value="login"></input>
</form>
<a href="signup.php">signup</a>

<?php
print $message;
?>
 
<?php 
Template::bottom();
?>
