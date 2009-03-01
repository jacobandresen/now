          function search_box() {
            var search_box=document.getElementById('search_box');
            var query = '';
            search_box.innerHTML = '<input type="text" name="query" id="query" value="'+query+'"> <input type="submit" onClick="search_result(1);">';
          }
          
          function search_result(page) {
            var query=document.getElementById('query');
            var search_result=document.getElementById('search_result');
            
            new Ajax.Request('service/pedant.dk/test.php?page='+page+'&query='+query.value,{
              method: 'get',
              onSuccess: function(transport, json ,resp){
            	 search_result.innerHTML=transport.responseText;
          		} 
            });

          }
