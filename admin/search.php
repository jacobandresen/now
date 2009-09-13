<?php
require_once("class_autoload.php");
 
Template::admintop();
?>

<h1> Search Test for <?=$_SESSION['account_domain'];?></h1>

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
$s=new Paging($_SESSION['account_id'], "search.php");
$s->page($sQuery, $iPage);
?>
</span>

<?php
Template::bottom();
?>

