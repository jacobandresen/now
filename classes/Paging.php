<?php
class Paging{

  public  $iOffset     = 0;
  public  $iLimit      = 20;
  public  $iCount      = 0;
  public  $iPage       = 1;
  public  $iPages      = 1;
  public $aTitles      = array();
  public $aColumns     = array();

  public $sPage = "";

  function __construct($sPage ){
    $this->sPage = $sPage; 
  }
  
  public function sNavigationFloat($iPage, $iPages, $sParams="", $iLimit=15 ){
    if ($iPages < 2){
      return '';
    }
    $sNavigation=""; 
    $iItems = 10;
    $iTotalPages = $iPages;
    $iStartPage = 1;
    $iAverage = floor($iItems/2);
    if($iPage>$iAverage) {
    	$iStartPage = $iPage-$iAverage;
    }
    
    $iMenuItem = 0;
    if ($iStartPage != 1){
    	$sNavigation .= '...';
    }
    for($iIndex = $iStartPage;$iIndex <= $iTotalPages;$iIndex++) {
  
      $iMenuItem++;
      if ($iPage == $iIndex){
	      $sNavigation .= ' | <b>' . $iIndex . '</b>';
      } else {
	      $sNavigation .= ' | <a href="'.$this->sPage.'&page='.($iIndex).'">'.$iIndex.'</a> ';
	    }
      if($iMenuItem==$iItems) break;
  
    }
    if ($iIndex < $iTotalPages){
    	$sNavigation .= '...';
    }

    if($iPage > 1) $sNavigation = ' | <a href="'.$this->sPage.'&page='.($iPage-1).'" class="nav">&lt;</a> ' . $sNavigation;
     else $sNavigation = ' | <b>&lt;</b> ' . $sNavigation;

      if($iPage != 1) $sNavigation = '<a href="'.$this->sPage.'&page=1" class="nav">&lt;&lt;</a> ' . $sNavigation;
	  else $sNavigation = '<b>&lt;&lt;</b> ' . $sNavigation;

    if($iPage < $iPages) 
  $sNavigation = $sNavigation . ' | <a  href="'.$this->sPage.'&page='.($iPage+1).'" class="nav">&gt;</a>';
  else $sNavigation = $sNavigation . ' | <b>&gt;</b>';

  if($iPage != $iPages) $sNavigation = $sNavigation . ' | <a  onClick="yase.result('.($iPages).');" class="nav">&gt;&gt;</a>';
  else $sNavigation = $sNavigation . ' | <b>&gt;&gt;</b>';

   print $sNavigation;
  }
  
  
  public function sNavigationFloat_OLD($iPage, $iPages, $sParams="", $iLimit=15 ){
    if ($iPages < 2){
      return '';
    }
  	$iItems = 10;
    $iTotalPages = $iPages;
    $iStartPage = 1;
    $iAverage = floor($iItems/2);
    if($iPage>$iAverage) {
    	$iStartPage = $iPage-$iAverage;
    }
    
    $iMenuItem = 0;
    if ($iStartPage != 1){
    	$sNavigation .= '...';
    }
    for($iIndex = $iStartPage;$iIndex <= $iTotalPages;$iIndex++) {
  
      $iMenuItem++;
	    if ($iPage == $iIndex){
	      $sNavigation .= ' | <b>' . $iIndex . '</b>';
      } else {
	      $sNavigation .= ' | <a href="?page=' . $iIndex . '&' . $sParams . '" class="nav">' . $iIndex . '</a> ';
	    }
      if($iMenuItem==$iItems) break;
  
    }
    if ($iIndex < $iTotalPages){
    	$sNavigation .= '...';
    }

    if($iPage > 1) $sNavigation = ' | <a href="?page=' . ($iPage-1) . '&' . $sParams . '" class="nav">&lt;</a> ' . $sNavigation;
      else $sNavigation = ' | <b>&lt;</b> ' . $sNavigation;

     if($iPage != 1) $sNavigation = '<a href="?page=1&' . $sParams . '" class="nav">&lt;&lt;</a> ' . $sNavigation;
	  else $sNavigation = '<b>&lt;&lt;</b> ' . $sNavigation;

	  if($iPage < $iPages) $sNavigation = $sNavigation . ' | <a href="?page=' . ($iPage+1) . '&' . $sParams . '" class="nav">&gt;</a>';
	  else $sNavigation = $sNavigation . ' | <b>&gt;</b>';

	  if($iPage != $iPages) $sNavigation = $sNavigation . ' | <a href="?page=' . ($iPages) . '&' . $sParams . '" class="nav">&gt;&gt;</a>';
	  else $sNavigation = $sNavigation . ' | <b>&gt;&gt;</b>';

    print $sNavigation;
  }

}
?>
