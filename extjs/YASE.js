YASE = Ext.extend(YASEUi, {
  loginWindow: new loginWindow() ,
  token:"", 
  tokenUrl:"token.php",
  appUrl:"app.php",
 
  initComponent: function() {
    YASE.superclass.initComponent.call(this);
    loginWindow.loginButton.on('click', this.getToken, this);
    loginWindow.show();
  }, 

  getToken: function () {
    var userName=loginWindow.userName.getValue();
    var password=loginWindow.password.getValue();

    Ext.Ajax.request({ 
      url: this.tokenUrl,
      params: {username:userName, password:password},
      success: function( msg ) {
                 YASE.token = msg.responseText;
                 loginWindow.close(); 
               }
    });
  } 
});
