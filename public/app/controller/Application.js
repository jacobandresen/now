Ext.define('now.controller.Application', {
    extend: 'Ext.app.Controller',

    views: [ 'Viewport', 'Application', 'Login' ],

    refs: [
        {ref: 'viewport', selector: 'viewport'},
        {ref: 'application', selector: 'application'},
        {ref: 'login', selector: 'login'}
    ],

    init: function () {
        this.control({ 'button[text=login]': { click: this.doLogin } });
    },

    doLogin: function () {
        var user = this.getLogin().down('textfield[name=user]').getValue();
        var pass = this.getLogin().down('textfield[name=pass]').getValue();
        Ext.Ajax.request({
            url: now.login,
            method: 'get',
            params: {
                user: user,
                pass: pass
            },
            success: function (response, request) {
                console.log("response:%o", response);
                var resp = Ext.decode( response.responseText);
                now.token = resp.token;
                this.getViewport().layout.setActiveItem(this.getApplication());
            },
            scope: this
        });
    }
});
