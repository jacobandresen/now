<?php
 require_once("classes/Global.php"); 
 require_once("classes/Framework.php");
 require_once("classes/Paging.php");

 $sAccount=$_REQUEST['account']; 
 $sQuery=$_REQUEST['query'];
 $iPage = $_REQUEST['page'];
 
 $y=new Yase($sAccount);
 $oPaging = new Paging();
 $sQuery = utf8_decode($sQuery);
 $iTotal = $y->oSearcher->iSearch($sQuery); 

 if($_GET['page'] < 1){
 	$iPage = 1;
 }
 $aRes = $y->oSearcher->aSearch($sQuery, $iPage);
 $iPages = (int) ((($iTotal-1)/$y->oSearcher->iLimit))+1;

 print '<div class="summary_info">The search for  <b>'.$sQuery.'</b> returned <b>'.$iTotal.'</b> results </div>';
 print '<div id="navigation">';
 $oPaging->sNavigationFloat($iPage, $iPages, '&query='.$sQuery, $y->oSearcher->iLimit );
 print '</div>';
 foreach ($aRes as $oRes){
   print '<div class="title"><a href="'.$oRes->sUrl.'">'.$oRes->sTitle.'</a></div>';
   print '<div id="content">'.$oRes->sContent.'</div>';
 }
 print '<div class="navigation">';
 $oPaging->sNavigationFloat($iPage, $iPages, '&query='.$sQuery, $y->oSearcher->iLimit );
 print '</div>';


?>
