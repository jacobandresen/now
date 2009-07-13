<?
 session_start();
 unset( $_SESSION['login'] );
 unset( $_SESSION['account_id'] );
 unset( $_SESSION['user_id']);

 header ( 'Location: index.php' ); 
?>

