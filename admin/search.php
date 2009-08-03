<?php
 require_once("../classes/YASE/Framework.php"); 
 require_once("../classes/Template.php");
  
 Template::admintop();
?>

<form action="#" method="get">
 <input type="hidden" name="account" value="<?php echo $sAccount?>"/>
 <input type="text" name="query"/>
 <input type="submit" name="search" value="search"/>
</form>

<?php
 $sQuery="";
 $iPage="";
 if(isset($_GET['query'])){ $sQuery=$_GET['query']; } 
 if(isset($_GET['page'])){ $iPage = $_GET['page']; } 
?>

<span>
<?php 
 $s=new YASE_Paging($_SESSION['account_id'], "search.php");
 $s->page($sQuery, $iPage);
?>
</span>
<?php
 Template::bottom();
?>

