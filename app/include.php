<?php
session_start();

function include_js(){
?>
  <script type="text/javascript" src="/jacob/yase/js/ext-3.0.0/adapter/ext/ext-base.js"></script>
  <script type="text/javascript" src="/jacob/yase/js/ext-3.0.0/ext-all.js"></script>
  <script type="text/javascript" src="/jacob/yase/js/jquery-1.3.2.min.js"></script> 

<?php
}

function include_css() { 
?>
  <link rel="stylesheet"  href="/jacob/yase/js/ext-3.0.0/resources/css/ext-all.css" type="text/css"/>
  
  <link rel="stylesheet" href="/jacob/yase/resources/css/main.css" type="text/css" /> 
  <link rel="stylesheet" href="/jacob/yase/resources/css/yase.css" type="text/css" /> 
<?php
}



?>
