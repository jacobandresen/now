<?php
class Template
{
    public static $root="/jacob/yase";
    public static $title='searchzen.org';
    public static $link='<a href="http://searchzen.org/jacob">Jacobs pages</a>';

    public function login () 
    {
        if(!(isset($_SESSION['user_id'])) )
        {
            header( 'Location: login.php');
        } 
    }

    public function include_js()
    {
?>
    <script type="text/javascript" src="<?=Template::$root?>/js/jquery-1.3.2.min.js"></script> 
    <script type="text/javascript" src="<?=Template::$root?>/js/ext-3.0.0/adapter/jquery/ext-jquery-adapter.js"></script>
    <script type="text/javascript" src="<?=Template::$root?>/js/ext-3.0.0/ext-all.js"></script>

<?php
    }

    public function include_css() 
    { 
?>
    <link rel="stylesheet"  href="<?=Template::$root?>/js/ext-3.0.0/resources/css/ext-all.css" type="text/css"/>
  
    <link rel="stylesheet" href="<?=Template::$root?>/css/main.css" type="text/css" /> 
    <link rel="stylesheet" href="<?=Template::$root?>/css/yase.css" type="text/css" /> 
<?php
    }

    private static function getTitle($tit)
    {
        if($tit!=""){
            return $tit;
        }else {
            return Template::$title;
        }   
    }

    public static function admintop() 
    {
        Template::login();
        Template::head();
        Template::leftbar();
    }

    public static function head($tit="") 
    {
 ?>
<html>
<head>
   <title> <?=Template::getTitle($tit) ?> </title>
  <?php Template::include_css() ; ?>
  <?php Template::include_js(); ?>
</head>
<body>

<div id="main">
   <div id="top">
   <?=Template::$link ?>
  </div>
 <div id="page">
  <div id="container"> 
<?php
    }

    public static function leftbar()
    {
?>
<div id="leftbar">
  <div id="innerleftbar">

<?php
    if (isset($_SESSION['user_id'])) {
?>

  <form action="<?=Template::$root?>/admin/account.php" onchange="this.submit()" method="post" >
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
    <li><a href="<?=Template::$root?>/admin/index.php">front page </a></li> 
    <li><a href="<?=Template::$root?>/admin/account.php">account </a></li> 
    <li><a href="<?=Template::$root?>/admin/crawler.php">crawl filter</a></li>
    <li><a href="<?=Template::$root?>/admin/indexer.php">index filter</a></li>
    <li><a href="<?=Template::$root?>/admin/search.php">search test</a></li>
    <li><a href="<?=Template::$root?>/admin/logout.php">logout</a></li> 
  </ul>
  <br><br>
<?php
    } else {
  ?>
  <ul>
   <li><a href="<?=Template::$root?>/admin/login.php">login</a></li>
  </ul>
  <?php
    }
?>
  </div> 
</div>

<?php
    }

    function bottom()
    {
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
