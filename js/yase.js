
var yase = {
 base:'/jka/yase/yase.php',
 account:'pedant.dk', 
 box:function() {
   $('#search_box').html('<input type="hidden" value="'+yase.account+'"></input><input type="text" name="query" id="query" value="" onkeydown="if(event.keyCode == 13){ yase.result(1,\''+yase.account+'\');}"> <input type="submit" value="search" onclick="yase.result(1,\''+yase.account+'\');">');
  },
  result:function (page) {
     $('#search_result').load(yase.base+'?query='+$('#query').val()+'&account='+yase.account+'&page='+page); 
  }
};

$(document).ready(function() {
  yase.box();
 });
