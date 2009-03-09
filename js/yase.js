
var yase = {
 base:'/yase/yase.php',
 box:function() {
   $('#search_box').html('<input type="text" name="query" id="query" value=""> <input type="submit" value="s&oslash;g" onclick="yase.result(1);">');
  },
  result:function (page) {
     $('#search_result').load(yase.base, 
             {'query':$('#query').val() } );
  }
}

$(document).ready(function() {
  yase.box();
 });
