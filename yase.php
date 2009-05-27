<html>
<head>
 <link rel="stylesheet" type="text/css" href="css/yase.css"/>
</head>
<body> 

<?php
 require_once("classes/Global.php"); 
 require_once("classes/Yase.php");
 require_once("classes/Paging.php");

 $sAccount="pedant.dk";
 $sQuery="";
 $iPage="";
 if(isset($_GET["account"])){ $sAccount=$_GET['account']; } 
 if(isset($_GET['query'])){ $sQuery=$_GET['query']; } 
 if(isset($_GET['page'])){ $iPage = $_GET['page']; } 
?>

<form action="yase.php" method="get">
 <input type="hidden" name="account" value="<?php echo $sAccount?>"/>
 <input type="text" name="query"/>
 <input type="submit" name="search" value="yase"/>
</form>

<?php
 $y=new Yase($sAccount);
  $y->page($sQuery, $iPage);
?>

</body>
</html>
