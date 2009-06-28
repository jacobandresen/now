<?php
 if(!(isset($_SESSION['user_id'])) ){
   header( 'Location: login.php');
 }
 if( !(isset($_POST['username']) || !(isset($_POST['password']) ) )) {
   header ('Location: login.php');
 }
?>

