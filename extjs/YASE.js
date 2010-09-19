YASE = Ext.extend(YASEUi, {
  appUrl:"app.php",
 
  initComponent: function() {
    YASE.superclass.initComponent.call(this);
    this.loginWindow = new LoginWindow({tokenUrl:'token.php'}); 
    this.loginWindow.on('login', this.handleLogin, this);
    this.loginWindow.show();
  }, 
 
  handleLogin: function ( token ) {
    console.log(token); 
    this.token = token;
    this.loginWindow.close();
  },
 
  getData: function (controller) {

  } 
});
