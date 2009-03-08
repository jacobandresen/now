<html>
<head>
 <title> Yet Another search engine - administration </title>

 <link type="text/css" href="http://jqueryui.com/latest/themes/base/ui.all.css" rel="stylesheet"/>
<script type="text/javascript" src="http://jqueryui.com/latest/jquery-1.3.2.js"></script>
<script type="text/javascript" src="http://jqueryui.com/ui/ui.core.js"></script>
<script type="text/javascript" src="http://jqueryui.com/ui/ui.tabs.js"></script>

<script type="text/javascript">
 $(document).ready(function() {
	 	$("#tabs").tabs();
   });
</script>

</head>

<body>

<div id="tabs">

 <b> Yet another search engine - administration </b>
 <ul>
  <li><a href="#fragment-1"><span>Account</span></a></li>
  <li><a href="#fragment-2"><span>Domain</span></a></li>
  <li><a href="#fragment-3"><span>Crawler </span></a></li>
  <li><a href="#fragment-4"><span>Indexer</span></a></li>
</ul>


 <div id="fragment-1" style="height:600px; width:400px">

 <button id="loginButton"> click this button to login</button>

 </div>
<div id="fragment-2" style="height:600px; width:400px">
add domain
</div>


<div id="fragment-3" style="height:600px; width:400px">

 crawling
</div>


<div id="fragment-4" style="height:600px; width:400px">
 indexing
</div>

</div>


</body>
</html>
