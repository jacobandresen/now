<?php
include ("include/Head.php");

if($yas->loggedIn()){
?>

<script src="js/SkipTable.js"></script>

<!-- domain -->
<b>domain:</b>pedant.dk <br>
<b>max documents crawled:</b>500 <br>
<b>max level distance:</b>20 <br>
<br/>
<br/>
<br/>

<!-- crawl skip mockup -->
<b>crawl skip:</b>
<table id="crawlSkip">
<tr>
  <td>.css</td>  
  <td onclick="javascript:edit(1)" class="edit">edit</td>  
  <td onclick="javascript:delete(1)" class="delete">delete</td> 
</tr>
<tr>
  <td>.flx</td> 
  <td onclick="javascript:edit(2)" class="edit">edit</td>  
  <td onclick="javascript:delete(2)" class="delete">delete</td>
</tr>
<tr>
  <td>&nbsp;</td>
  <td>&nbsp;</td> 
  <td>new</td> 
</tr>
</table>

<button id="crawlButton">crawl</button>

<hr/>
<!-- index skip mockup-->

<b>index skip:</b>

<br/>
<br/>
<br/>


<!-- index skips -->
<table id="indexSkip">
<tr><td>.css</td>       <td>edit</td>   <td>delete</td> </tr>
<tr><td>.flx</td>       <td>edit</td>   <td>delete</td> </tr>
<tr><td>&nbsp;</td>     <td>&nbsp;</td> <td>new</td>    </tr>
</table>

<button id="indexButton">index</button>


<hr/>

<?php
}
include ("include/Bottom.php");
?>
