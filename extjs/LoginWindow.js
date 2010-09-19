LoginWindow = Ext.extend(LoginWindowUi, {
  
  initComponent: function(config) {
    Ext.apply(config); 
    LoginWindow.superclass.initComponent.call(this);
    this.addEvents(['login']); 
    this.loginButton.on('click', this.getToken, this);
  },

  getToken: function () {

    Ext.Ajax.request({
      url: this.tokenUrl,
      params: {username:this.userName.getValue(),
               password:this.password.getValue()},
      success: function ( msg ) {
                 var token = msg.responseText;
                
                 if (token !== '') {
                   this.fireEvent('login',token);
                 } else {
                   Ext.MessageBox.alert('Login failed');
                 }

               },
      scope: this
     });
  }
});
