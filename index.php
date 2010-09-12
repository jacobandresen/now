<?php 
 require_once('php/ExtJs.php');
?>
<html>
<head>
<?php ExtJs::includeCacheFly();?>
<?php ExtJs::includeDesignerDirectory('extjs');?> 
</head>
<body>

<script type="text/javascript">
  var body = new AdministrationPanel({
     renderTo : Ext.getBody() 
  });
  body.show();
  console.log('hello world');
</script>

</body>
</html>
