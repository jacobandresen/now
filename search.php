<?php
 require_once("classes/Yase.php");
 require_once("app/template.php");
 
 head("Yet Another Search Engine");
 leftbar();
?>


<form action="#" method="get">
 <input type="hidden" name="account" value="<?php echo $sAccount?>"/>
 <input type="text" name="query"/>
 <input type="submit" name="search" value="search"/>
</form>

<?php
 $sAccount="pedant.dk";
 $sQuery="";
 $iPage="";
/* if(isset($_GET["account"])){ $sAccount=$_GET['account']; } */
 if(isset($_GET['query'])){ $sQuery=$_GET['query']; } 
 if(isset($_GET['page'])){ $iPage = $_GET['page']; } 
 

 $y=new Yase($sAccount);
 $y->page($sQuery, $iPage);

 bottom();
?>

