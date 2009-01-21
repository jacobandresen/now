<?php
$yas =new Yas();
$yas->login($yas->sLogin, $yas->sPassword);

$yas->setup();
if(!($yas->LoggedIn())){
?>
<form action="index.php" method="post">
<table>
<tr>
 <td colspan="2" align="center">
  <b>please login</b>
  </td>
</tr>
<tr>
 <td>
   login
 </td> 
 <td>
  <input type="text" name="login"/>
 </td>
</tr>
<tr>
 <td>
   password 
 </td>
 <td>
  <input type="password" name="password"/>
 </td>
</tr>
<tr>
 <td>&nbsp;</td>
 <td align="right">
   <input type="submit" value="login"/>
 <td>
</tr>
</form>
</table>
<?php
}
?>
