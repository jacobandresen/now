YASE = Ext.extend(YASEUi, {
  tokenUrl:"token.php",
  appUrl:"app.php",
 
  initComponent: function() {
    YASE.superclass.initComponent.call(this);
    this.loginWindow = new LoginWindow({tokenUrl:'token.php'}); 
    this.loginWindow.on('login', this.handleLogin, this);
    this.loginWindow.show();
  }, 
 
  handleLogin: function ( msg ) {
    this.loginWindow.close();
  },
 
  getData: function (controller) {

  } 
});
