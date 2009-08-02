<?php
require_once("include.php");
function head($title) {
?>
<html>
<head>
  <title> <?php print $title ?> </title>
  <?php include_css() ; ?>
  <?php include_js(); ?>
</head>
<body>

<div id="main">
  <div id="page">
   <div id="top">
   <?php print $title ?>
  </div>
  <div id="container"> 
<?php
}
function leftbar(){
?>
<div id="leftbar">
  <div id="innerleftbar">

<?php
  if (isset($_SESSION['user_id'])) {
?>

  <form action="/jacob/yase/account.php" onchange="this.submit()" method="post" >
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
    <li><a href="/jacob/yase/logout.php">logout</a></li> 
    <li><a href="/jacob/yase/account.php">account </a></li> 
    <li><a href="/jacob/yase/crawler.php">crawl filter</a></li>
    <li><a href="/jacob/yase/indexer.php">index filter</a></li>
    <li><a href="/jacob/yase/body.php">body filter </a></li>
    <li><a href="/jacob/yase/search.php">search test</a></li>
  </ul>
  <br><br>

  <!-- TODO: only render this when user is superuser -->
  <ul>  
   <li><a href="/jacob/yase/admin.php">admin</a></li>
   </ul>
<?php
 }else {
  ?>
  <ul>
   <li><a href="/jacob/yase/login.php">login</a></li>
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
?>
