<?php
function loginForm() {
?>
<form class="userform" action="index.php" method="POST">
 <div class="fieldlabel">name </div>
 <div class="fieldvalue"> 
 <input type="text" name="username"></input>
 </div>
 <div class="fieldlabel">password </div>
 <div class="fieldvalue"> 
 <input type="password" name="password"></input>
 </div> 
 <div>
 <input type="submit" value="login"></input>
</form>

<?php
}
?>
