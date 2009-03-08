

var search = {
 box:function() {
  var search_box=document.getElementById('search_box');
  var query = '';
  search_box.innerHTML = '<input type="text" name="query" id="query" value="'+query+'"> <input type="submit" onClick="search_result(1);">';
  } ,
          
  result:function (page) {
    var query=document.getElementById('query');
    $('#search_result').load('test/SearcherTest.php');
  }
}
