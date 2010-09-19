<?php 
 require_once('php/ExtJs.php');
?>
<html>
<head>
<?php 
  ExtJs::cacheFly();
  ExtJs::import('LoginWindow');
  ExtJs::import('YASE');
?>

</head>
<body>

<script type="text/javascript">
  Ext.QuickTips.init();
  var body = new YASE({
     renderTo : Ext.getBody() 
  });
  body.show();
</script>

</body>
</html>
