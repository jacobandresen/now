<?php

session_start();

//templating system used in admin app and index files
class Template{

  //configurable options
  public static $root="/jacob/yase";
  public static $title="Yet another search engine";


  public function login () {
    if(!(isset($_SESSION['user_id'])) ){
      header( 'Location: login.php');
    }
  }

  public function include_js(){
?>
  <script type="text/javascript" src="<?=Template::$root?>/js/ext-3.0.0/adapter/ext/ext-base.js"></script>
  <script type="text/javascript" src="<?=Template::$root?>/js/ext-3.0.0/ext-all.js"></script>
  <script type="text/javascript" src="<?=Template::$root?>/js/jquery-1.3.2.min.js"></script> 

<?php
}

  public function include_css() { 
?>
  <link rel="stylesheet"  href="<?=Template::$root?>/js/ext-3.0.0/resources/css/ext-all.css" type="text/css"/>
  
  <link rel="stylesheet" href="<?=Template::$root?>/resources/css/main.css" type="text/css" /> 
  <link rel="stylesheet" href="<?=Template::$root?>/resources/css/yase.css" type="text/css" /> 
<?php
}

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
  <?php Template::include_css() ; ?>
  <?php Template::include_js(); ?>
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

  <form action="<?=Template::$root?>/adm/account.php" onchange="this.submit()" method="post" >
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
    <li><a href="<?=Template::$root?>/adm/logout.php">logout</a></li> 
    <li><a href="<?=Template::$root?>/adm/account.php">account </a></li> 
    <li><a href="<?=Template::$root?>/adm/crawler.php">crawl filter</a></li>
    <li><a href="<?=Template::$root?>/adm/indexer.php">index filter</a></li>
    <li><a href="<?=Template::$root?>/adm/body.php">body filter </a></li>
    <li><a href="<?=Template::$root?>/adm/search.php">search test</a></li>
  </ul>
  <br><br>

  <!-- TODO: only render this when user is superuser -->
  <ul>  
   <li><a href="<?=Template::$root?>/adm/admin.php">admin</a></li>
   </ul>
<?php
 }else {
  ?>
  <ul>
   <li><a href="<?=Template::$root?>/adm/login.php">login</a></li>
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
