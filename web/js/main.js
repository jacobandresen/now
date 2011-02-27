//2011, Jacob Andresen <jacob.andresen@gmail.com>
function buildYASE(token) {
    var YASE =new Ext.Viewport({
        token: token, 
        layout: 'border',
        items: [{
            xtype: 'accountform',
            region: 'north' 
        }, 
        {
            title:'list',
            region:'center',
            xtype:'tabpanel',
            items: [
                {xtype:'grid', title:'domains'},
                {xtype:'grid', title:'filters'}
            ]
        }]
    });
    return YASE;
};

var yase;

Ext.onReady(function() {
    var loginWindow = new YASE.LoginWindow({tokenUrl:'token.php'});
    loginWindow.on('login',  function (token) {
        yase = buildYASE(token); 
        loginWindow.close();
    });
    loginWindow.show();
});
