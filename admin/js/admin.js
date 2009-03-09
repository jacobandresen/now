
function renderDomainOptions(){
 $('#fragment-2').html ('hello world 1');
}
function renderCrawlerOptions(){
  $('#fragment-3').html ('hello world 2');
}

function renderIndexerOptions(){
  $('#fragment-4').html ('hello world 3');
}

$(document).ready(function(){

    renderDomainOptions(); 
    renderCrawlerOptions();
    renderIndexerOptions();

    //prepare tabs 
    $("#tabs").tabs( {selectable: true});
    $("#tabs").tabs('option', 'disabled', [1,2,3,4]);
    $("#loginButton").click(function(){
        $("#tabs").tabs('option', 'disabled', []);
    });

});
