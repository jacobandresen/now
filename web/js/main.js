var loginWindow = new YASE.LoginWindow({tokenUrl:'token.php'});
var token = "";

var viewPort = new Ext.ViewPort({

});

loginWindow.on('login',  function (token) {
    token = token;
    loginWindow.close();
});

loginWindow.show();
