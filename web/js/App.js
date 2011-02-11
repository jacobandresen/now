

var loginWindow = new YASE.LoginWindow({tokenUrl:'token.php'});
var token = "";

loginWindow.on('login',  function (token) {
    token = token;
    loginWindow.close();
});

loginWindow.show();
