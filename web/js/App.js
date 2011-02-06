Ext.namespace('YASE');

YASE.App = Ext.extend(Ext.ViewPort, {

    initComponent: function(config) {
        this.items = [
            {
                xtype: 'tabpanel',
                title: 'Search Engine Administration',
                region: 'center',
                activeTab: 0
            }
        ];

        var defaultConfig = {
            layout: 'border',
            appUrl: 'app.php',
            tokenUrl: 'token.php',
            renderTo: Ext.getBody()
        };

        Ext.apply(config, defaultConfig);

        YASE.App.superclass.initComponent.call(this);
    },

    initEvents: function () {
        this.loginWindow = new YASE.LoginWindow({tokenUrl:tokenUrl});
        this.loginWindow.on('login', this.doLogin, this);
        this.loginWindow.show();
    },

    doLogin: function ( token ) {
        this.token = token;
        this.loginWindow.close();
    }
});

new YASE.App({});
