Ext.namespace('YASE');

YASE.App = Ext.extend(Ext.ViewPort, {

    layout: 'border',
    appUrl: 'app.php',
    tokenUrl: 'token.php',
    renderTo: Ext.getBody(),
 
    initComponent: function() {
        this.items = [
        {
            xtype: 'tabpanel',
            title: 'Search Engine Administration',
            region: 'center',
            activeTab: 0
        }
        ];

    YASE.App.superclass.initComponent.call(this);
  },

  initEvents: function () {
    this.loginWindow = new YASE.LoginWindow({tokenUrl:tokenUrl});
    this.loginWindow.on('login', this.handleLogin, this);
    this.loginWindow.show();
  },

  handleLogin: function ( token ) {
    this.token = token;
    this.loginWindow.close();
  }
});

new YASE.App({});
