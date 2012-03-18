Ext.define('now.controller.Searcher', {
    extend: 'Ext.app.Controller',

    views: ['Searcher', 'ResultList'],
    stores: ['Result'],

    refs: [ {
        ref: 'searcher',
        selector: 'searcher'
    },{
        ref: 'button',
        selector: 'button'
    },{
        ref: 'resultlist',
        selector: 'resultlist'
    }],

    init: function () {
        this.control({
            button: {
                click: this.doSearch
            },
            resultlist: {
                click: function (a,b,c){
                    console.log("click list: %o, %o, %o", a,b,c);
                }
            }
        });
    },

    doSearch: function () {
        var query = this.getSearcher().down("textfield").getValue();
        var resultList = this.getResultlist();

        Ext.Ajax.request({
            url: now.search,
            method: 'get',
            params:  {
                user : now.user,
                pass : now.pass,
                query: query
            },
            success: function (response, request) {
                var data = Ext.decode (response.responseText );
                console.log("data:%o", data);
                resultList.store.loadData( data, false);
            },
            scope: this
        });
    }
});
