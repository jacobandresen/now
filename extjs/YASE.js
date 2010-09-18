YASE = Ext.extend(YASEUi, {
  loginWindow: new LoginWindow() ,
  token:"", 
  tokenUrl:"token.php",
  appUrl:"app.php",
 
  initComponent: function() {
    YASE.superclass.initComponent.call(this);
    this.loginWindow.loginButton.on('click', this.getToken, this);
    this.loginWindow.show();
  }, 

  getToken: function () {
    var userName=this.loginWindow.userName.getValue();
    var password=this.loginWindow.password.getValue();

    Ext.Ajax.request({ 
      url: this.tokenUrl,
      params: {username:userName, password:password},
      success: function( msg ) {
                 YASE.token = msg.responseText;
                 if (YASE.token!=='') {
                   this.loginWindow.close(); 
                   console.log("got token:"+YASE.token);
                 } else {
                   console.log("login failed");
                 } 
               },
      scope: this
    });
  },
 
  getData: function (controller) {

  } 
});
