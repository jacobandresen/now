<?php 
 require_once('php/ExtJs.php');
?>
<html>
<head>
<?php ExtJs::includeCacheFly();?>
<?php ExtJs::includeDirectory('extjs');?> 
</head>
<body>

<script type="text/javascript">
  var body = new AdministrationPanel({
     renderTo : Ext.getBody()
  });
  body.show();
</script>

</body>
</html>
