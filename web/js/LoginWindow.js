LoginWindow = Ext.extend(LoginWindowUi, {
  
  initComponent: function(config) {
    Ext.apply(config); 
    LoginWindow.superclass.initComponent.call(this);
    this.addEvents(['login']); 
    this.loginButton.on('click', this.getToken, this);
  },

  getToken: function () {
    request = new Ajax.Request(
      this.tokenUrl,
        {
          parameters:{username:this.userName.getValue(),
                  password:this.password.getValue()},
          asynchronous: false, 
          onSuccess: function ( msg ) {
                 var token = msg.responseText;
                 this.token = token; 
                  if (token !== '') {
                   this.fireEvent('login',token);
                 } else {
                   Ext.MessageBox.alert('Login failed');
                 }
              }.bind(this)
       });
   }
});
