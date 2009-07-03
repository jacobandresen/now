<?php
 require_once("classes/Yase.php");
 require_once("app/template.php");

 $sAccount="pedant.dk";
 $sQuery="";
 $iPage="";
 
 $y=new Yase($sAccount);
 
 if(isset($_GET["account"])){ $sAccount=$_GET['account']; } 
 if(isset($_GET['query'])){ $sQuery=$_GET['query']; } 
 if(isset($_GET['page'])){ $iPage = $_GET['page']; } 
 
 head("yet another search");
?>

<form action="search.php" method="get">
 <input type="hidden" name="account" value="<?php echo $sAccount?>"/>
 <input type="text" name="query"/>
 <select name="account">
   <option value="pedant.dk">pedant.dk</option>
   <option value="kruse-net.dk">kruse-net.dk</option> 
   <option vlaue="www.xn--schler-dya.net">www.xn--schler-dya.net</option>
   <option value="www3.swehockey.se">swehockey</option> 
   <option value="jaksm.dk">jaksm.dk</option>
 </select>

 <input type="submit" name="search" value="search"/>
 <a href="/jacob/yase">meditate</a>
</form>

<?php
 $y->page($sQuery, $iPage);
 bottom();
?>

