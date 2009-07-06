<?php
session_start();
function head($title) {
?>
<html>
<head>
  <title> <?php print $title ?> </title>
  <link rel="stylesheet"  href="/jacob/yase/js/ext-3.0-rc2/resources/css/ext-all.css" type="text/css"/>
  
  <link rel="stylesheet" href="/jacob/yase/resources/css/main.css" type="text/css" /> 
  <link rel="stylesheet" href="/jacob/yase/resources/css/yase.css" type="text/css" /> 
  <script type="text/javascript" src="/jacob/yase/js/ext-3.0-rc2/adapter/ext/ext-base.js"></script>
  <script type="text/javascript" src="/jacob/yase/js/ext-3.0-rc2/ext-all.js"></script>
  <style type="text/css">
    .add {
      background-image:url(/jacob/yase/resources/icons/fam/add.gif) !important;
    }
    .delete {
      background-image:url(/jacob/yase/resources/icons/fam/delete.gif) !important;
    }
    .save {
      background-image:url(/jacob/yase/resources/icons/save.gif) !important;
    }
  </style>
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
  if (isset($_SESSION['login'])) {
?>
  <select id="account" name="account" style="width:100px" 
    onchange="alert('changed account')">
   <option value="pedant.dk">pedant.dk</option>
   <option value="johanbackstrom.se">johanbackstrom.se</option>
  </select>
  <br><br>
   <ul>
    <li><a href="/jacob/yase/logout.php">logout</a></li> 
    <li><a href="/jacob/yase/account.php">account info</a></li> 
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
