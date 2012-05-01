Ext.define('now.controller.Searcher', {
    extend: 'Ext.app.Controller',

    views: ['Viewport', 'Searcher', 'ResultList'],
    stores: ['Result'],

    refs: [
        {ref: 'viewport', selector: 'viewport' },
        {ref: 'searcher', selector: 'searcher' },
        {ref: 'resultlist', selector: 'resultlist'}
    ],

    init: function () {
        this.control({
            "button[text=search]": {
                click: this.doSearch
            },
            resultlist: {
                select: function (grid){
                    window.open ( grid.selected.items[0].get("url"), "linkwindow");
                }
            },
            "searcher textfield": {
               specialkey: function (field, e) {
                   if (e.getKey() == e.ENTER) {
                       this.doSearch()
                   }
               }
            }
        });
    },

    doSearch: function () {
        var query = this.getSearcher().down("textfield").getValue();
        var resultList = this.getResultlist();

        Ext.Ajax.request({
            url: "search.php",
            method: 'get',
            params:  {
                token: now.token,
                query: query
            },
            success: function (response, request) {
                var data = Ext.decode (response.responseText );
                resultList.store.loadData( data, false);
            },
            scope: this
        });
    }
});
