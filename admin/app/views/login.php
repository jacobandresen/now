<?php
function loginForm() {
?>
<form class="login" action="login.php" method="POST">
 <div >name </div>
 <div> 
 <input type="text" name="username"></input>
 </div>
 <div>password </div>
 <div> 
 <input type="password" name="password"></input>
 </div> 
 <div>
 <input type="submit" value="login"></input>
</form>

<a href="signup.php>signup</a>
<?php
}
?>
