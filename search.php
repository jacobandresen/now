<?php
 require_once("app/template.php");
 require_once("app/login.php");
 require_once("classes/Global.php");
 require_once("classes/Searcher.php");
 require_once("classes/Paging.php"); 
 
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
 if(isset($_GET['query'])){ $sQuery=$_GET['query']; } 
 if(isset($_GET['page'])){ $iPage = $_GET['page']; } 
 
 $s=new Paging($sAccount, "search.php");
 $s->page($sQuery, $iPage);

 bottom();
?>

