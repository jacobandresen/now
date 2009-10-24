<?php
class Searcher
{
  private $iAccountId;
  public $iLimit = 5;

  public function __construct($iAccountId)
  {
    $this->iAccountId=$iAccountId;
  }

  public function aSearch ($query, $iPage)
  {
    $aRet = array();
    $sLimit = '';
    if ($iPage != 0){
      $iOffset = ($iPage*$this->iLimit) - $this->iLimit;
      $sLimit = " LIMIT " . $this->iLimit . " OFFSET $iOffset";
    }

    if($query!=""){
      $result = mysql_query("SELECT *, MATCH(content) AGAINST('$query') AS score FROM document WHERE MATCH(content) AGAINST('$query') and account_id='".$this->iAccountId."' ORDER BY score DESC ".$sLimit); 
      while ($row=mysql_fetch_array($result)){
        $title=$row['title'];
        $content=$row['content'];
        $content = preg_replace('/\&.*?\;/is',' ', $content);

        $oResult = new Result();
        $oResult->sUrl = urldecode($row['url']);
        $oResult->sTitle = trim(html_entity_decode($title));
        if($oResult->sTitle==""){ $oResult->sTitle = $oResult->sUrl; }
        $oResult->sContent = substr($content, 1, 400);
        $aRet[] = $oResult;
      }
    }
    return $aRet;
  }

  public function iSearch ($query)
  {
    $aRet = array();
    if ($query != "") {
      $sSQL = "SELECT count(title) AS cnt
        FROM document 
        WHERE MATCH(content) AGAINST('$query') and account_id='".$this->iAccountId."'";
      $result = mysql_query( $sSQL ) or die ( mysql_error());
      $row = mysql_fetch_array($result);
      return $row['cnt'];
    }
    return 0;
  }
}

class Paging extends Searcher
{
  public $iOffset     = 0;
  public $iLimit      = 20;
  public $iCount      = 0;
  public $iPage       = 1;
  public $iPages      = 1;
  public $aTitles      = array();
  public $aColumns     = array();
  public $sPage = "";

  public function __construct($sAccount, $sPage )
  {
    parent::__construct($sAccount); 
    $this->sPage = $sPage; 
  }

  public function sNavigationFloat($iPage, $iPages, $sParams="", $iLimit=15 )
  {
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

  public function page($query, $page)
  {
    $total = $this->iSearch($query);
    if(!isset($_REQUEST['page']) || $_GET['page'] < 1){
      $page = 1;
    }
    $results = $this->aSearch($query, $page);
    $pages = (int) ((($total-1)/$this->iLimit))+1;
    if ($query!=""){
      print '<div class="summary_info">The search for  <b>'.$query.'</b> returned <b>'.$total.'</b> results </div>';
    }
    print '<div class="navigation">';
    $this->sNavigationFloat($page, $pages, 'account='.$REQUEST['account'].'&query='.$query, $this->searcher->iLimit );
    print '</div>';

    foreach ($results as $res){
      print '<div class="title"><a href="'.$res->sUrl.'" target="_parent">'.$res->sTitle.'</a></div>';
      print '<div class="content">'.$res->sContent.'</div>';
    }
    print '<div class="navigation">';
    $this->sNavigationFloat($page, $pages, '&query='.$query, $this->searcher->iLimit );
    print '</div>';
  }
};
?>
