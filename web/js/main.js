
var viewport = new Ext.Viewport({
    token: "", 
    layout: 'border',
    items: [{
        region:'menu',
        title:'north'
    }, 
    {
        region:'center',
        title:'list' 
    }]
});

var loginWindow = new YASE.LoginWindow({tokenUrl:'token.php'});

loginWindow.on('login',  function (token) {
    viewport.token = token;
    loginWindow.close();
});

loginWindow.show();
