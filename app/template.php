<?php

function head($title) {
?>
<html>
<head>
  <title> <?php print $title ?> </title>
  <link rel="stylesheet"  href="js/ext-3.0-rc2/resources/css/ext-all.css" type="text/css"/>
  
  <link rel="stylesheet" href="resources/css/yase.css" type="text/css" /> 
  <script type="text/javascript" src="js/ext-3.0-rc2/adapter/ext/ext-base.js"></script>
  <script type="text/javascript" src="js/ext-3.0-rc2/ext-all.js"></script>
  <script type="text/javascript" src="js/SettingsGrid.js"></script>
  <script type="text/javascript" src="js/Admin.js"></script>
  <script type="text/javascript" src="js/RowEditor.js"></script>

  <style type="text/css">
    .add {
      background-image:url(resources/icons/fam/add.gif) !important;
    }
    .delete {
      background-image:url(resources/icons/fam/delete.gif) !important;
    }
    .save {
      background-image:url(resources/icons/save.gif) !important;
    }
  </style>
</head>
<body>

<?php
}

function bottom(){
?>
</body>
</html>
<?php
}
?>
