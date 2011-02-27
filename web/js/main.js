//2011, Jacob Andresen <jacob.andresen@gmail.com>
Ext.namespace("YASE");

function buildYASE(token) {
    return (new Ext.Viewport({
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
    }));
};
var token;

Ext.onReady(function() {
    var loginWindow = new YASE.LoginWindow({tokenUrl:'token.php'});
    loginWindow.on('login',  function (token) {
        token = token; 
        yase = buildYASE(); 
        loginWindow.close();
    });
    loginWindow.show();
});
