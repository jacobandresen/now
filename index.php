<?php
require_once ("include/Head.php");

if($yas->loggedIn()){
?>
<script type="text/javascript" src="js/mootools-nc.js"></script>
<script type="text/javascript" src="js/SkipTable.js"></script>

<b>crawl skip:</b>
<div id="crawlSkip"> </div>

<b>index skip:</b>
<div id="indexSkip"> </div>

<script type="text/javascript">
  var sTicket="1234567890"; 
  var oCrawlSkip=new SkipTable("data.php","oCrawlSkip", "crawlSkip");
  oCrawlSkip.update();
  var oIndexSkip=new SkipTable("data.php", "oIndexSkip", "indexSkip");
  oIndexSkip.update();
</script>
<?php
}
include ("include/Bottom.php");
?>
