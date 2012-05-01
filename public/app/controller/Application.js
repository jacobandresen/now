Ext.define('now.controller.Application', {
    extend: 'Ext.app.Controller',

    views: [
        'Viewport',
        'Application',
        'Searcher',
        'Login'
    ],

    refs: [
        {ref: 'main', selector: '#main'},
        {ref: 'viewport', selector: 'viewport'},
        {ref: 'searcher', selector: 'searcher'},
        {ref: 'application', selector: 'application'},
        {ref: 'login', selector: 'login'}
    ],

    init: function () {
        this.control({
            "button[text=logout]": {
                 click: this.doLogout
             },
            "button[text=search]": {
                 click: this.doSearch
            }
        });
    },

    doLogout: function () {
        this.getViewport().layout.setActiveItem(this.getLogin());
    },

    doSearch: function () {
        this.getMain().layout.setActiveItem(this.getSearcher());
    }
});
