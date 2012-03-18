Ext.define('now.controller.Searcher', {
    extend: 'Ext.app.Controller',

    views: ['Searcher', 'ResultList'],
    stores: ['Result'],

    refs: [
        {ref: 'searcher', selector: 'searcher' },
        {ref: 'resultlist', selector: 'resultlist'}
    ],

    init: function () {
        this.control({
            'button[text=search]': {
                click: this.doSearch
            },
            resultlist: {
                select: function (grid){
                    window.location = grid.selected.items[0].get("url");
                }
            }
        });
    },

    doSearch: function () {
        var query = this.getSearcher().down("textfield").getValue();
        var resultList = this.getResultlist();

        console.log("SEARCH");
        Ext.Ajax.request({
            url: now.search,
            method: 'get',
            params:  {
                token: now.token,
                query: query
            },
            success: function (response, request) {
                console.log("search response:%o", response);
                var data = Ext.decode (response.responseText );
                resultList.store.loadData( data, false);
            },
            scope: this
        });
    }
});
