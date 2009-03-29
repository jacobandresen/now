<html>
<head>
 <link rel="stylesheet" type="text/css" href="css/yase.css"/>
</head>
<body> 

<?php
 require_once("classes/Global.php"); 
 require_once("classes/Framework.php");
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
 $oPaging = new Paging("yase.php?account=".$sAccount."&query=".$sQuery);
 $sQuery = utf8_decode($sQuery);
 $iTotal = $y->oSearcher->iSearch($sQuery); 

 if(isset($_REQUEST['page']) && $_GET['page'] < 1){
 	$iPage = 1;
 }
 $aRes = $y->oSearcher->aSearch($sQuery, $iPage);
 $iPages = (int) ((($iTotal-1)/$y->oSearcher->iLimit))+1;
 print '<div class="summary_info">The search for  <b>'.$sQuery.'</b> returned <b>'.$iTotal.'</b> results </div>';
 print '<div class="navigation">';
 $oPaging->sNavigationFloat($iPage, $iPages, '&query='.$sQuery, $y->oSearcher->iLimit );
 print '</div>';
 foreach ($aRes as $oRes){
   print '<div class="title"><a href="'.$oRes->sUrl.'" target="_parent">'.$oRes->sTitle.'</a></div>';
   print '<div class="content">'.$oRes->sContent.'</div>';
 }
 print '<div class="navigation">';
 $oPaging->sNavigationFloat($iPage, $iPages, '&query='.$sQuery, $y->oSearcher->iLimit );
 print '</div>';
?>

</body>
</html>
