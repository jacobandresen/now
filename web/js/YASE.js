YASE = Ext.extend(Ext.Viewport, {
  layout: 'border',
  appUrl:"app.php",
 
  initComponent: function() {
    this.items = [
      {
        xtype: 'tabpanel',
        title: 'Search Engine Administration',
        region: 'center',
        activeTab: 0
      }
    ];

    YASE.superclass.initComponent.call(this);
    this.loginWindow = new LoginWindow({tokenUrl:'token.php'});
    this.loginWindow.on('login', this.handleLogin, this);
    this.loginWindow.show();
  },

  handleLogin: function ( token ) {
    this.token = token;
    this.loginWindow.close();
  }
 
});