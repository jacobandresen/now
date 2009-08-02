<?php
require_once("include.php");

class Template{

  public static $root="/jacob/yase";
  public static $title="Yet another search engine";

  private static function getTitle($tit){
    if($tit!=""){
     return $tit;
   }else {
     return Template::$title;
   }   
  }

  public static function head($tit="") {

 ?>
<html>
<head>
   <title> <?=Template::getTitle($tit) ?> </title>
  <?php include_css() ; ?>
  <?php include_js(); ?>
</head>
<body>

<div id="main">
  <div id="page">
   <div id="top">
   <?=Template::getTitle($tit) ?>
  </div>
  <div id="container"> 
<?php
}

  public static function leftbar(){
?>
<div id="leftbar">
  <div id="innerleftbar">

<?php
  if (isset($_SESSION['user_id'])) {
?>

  <form action="<?=Template::$root?>/account.php" onchange="this.submit()" method="post" >
  <select id="accountSelect" name="account_id" style="width:100px">
<?php
  foreach (User::getAccounts($_SESSION['user_id']) as $a ) {
     if ( trim($a->sDomain) == trim($_SESSION["account_domain"])) {
      print "<option value=\"".$a->iId."\" selected>".trim($a->sDomain); 
     }else{ 
       print "<option value=\"".$a->iId."\">".trim($a->sDomain); 
     }
    }
?>
  </select>
  </form>
 <br><br>
   <ul>
    <li><a href="<?=Template::$root?>/logout.php">logout</a></li> 
    <li><a href="<?=Template::$root?>/account.php">account </a></li> 
    <li><a href="<?=Template::$root?>/crawler.php">crawl filter</a></li>
    <li><a href="<?=Template::$root?>/indexer.php">index filter</a></li>
    <li><a href="<?=Template::$root?>/body.php">body filter </a></li>
    <li><a href="<?=Template::$root?>/search.php">search test</a></li>
  </ul>
  <br><br>

  <!-- TODO: only render this when user is superuser -->
  <ul>  
   <li><a href="<?=Template::$root?>/admin.php">admin</a></li>
   </ul>
<?php
 }else {
  ?>
  <ul>
   <li><a href="<?=Template::$root?>/login.php">login</a></li>
  </ul>
  <?php
 }
?>
  </div> 
</div>

<?php
}

function bottom(){
?>
  </div> 
  </div>
</div>
</body>
</html>
<?php
}

}
?>
