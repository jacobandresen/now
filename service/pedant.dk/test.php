<?php
  require_once('../../classes/Global.php'); 
  require_once('../../classes/Search/Framework.php');
  require_once('../../classes/Paging.php');

  $s=new Searcher("pedant_dk");
  $oPaging = new Paging();
  $iTotal = $s->iSearch($_GET['query']);
  $iPage = $_GET['page'];
  if ($_GET['page'] < 1){
    $iPage = 1;
  }
  $aRes = $s->aSearch($_GET['query'], $iPage);
  $iPages = (int)((($iTotal-1)/$s->iLimit))+1;

  
  print '<div id="navigation">';
  $oPaging->sNavigationFloat($iPage, $iPages, '&query='.$_GET['query'], $s->iLimit );
  print '</div>';
  foreach ($aRes as $oRes){
    print '<div id="title"><a href="'.$oRes->sUrl.'">'.$oRes->sTitle.'</a></div>';
    print '<div id="content">'.$oRes->sContent.'</div>';
  }
  print '<div id="navigation">';
  $oPaging->sNavigationFloat($iPage, $iPages, '&query='.$_GET['query'], $s->iLimit );
  print '</div>';

?>
