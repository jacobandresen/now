Ext.define('now.controller.Login', {
    extend: 'Ext.app.Controller',

    views: [ 'Viewport', 'Application', 'Login' ],

    refs: [
        {ref: 'viewport', selector: 'viewport'},
        {ref: 'application', selector: 'application'},
        {ref: 'login', selector: 'login'}
    ],

    init: function () {
        this.control({
            "button[text=login]": {
                 click: this.doLogin
             },
            "login textfield": {
                 specialkey: function (field,e) {
                     if (e.getKey() == e.ENTER) {
                         this.doLogin();
                     }
                 }
            }
        });
    },

    doLogin: function () {
        var user = this.getLogin().down('textfield[name=user]').getValue();
        var pass = this.getLogin().down('textfield[name=pass]').getValue();
        Ext.Ajax.request({
            url: "login.php",
            method: 'get',
            params: {
                user: user,
                pass: pass
            },
            success: function (response, request) {
                var resp = Ext.decode( response.responseText);
                now.token = resp.token;
                if (now.token !== "") {
                    this.getViewport().layout.setActiveItem(this.getApplication());
                }
            },
            scope: this
        });
    }
});
