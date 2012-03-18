Ext.define('now.view.Login', {
    extend: 'Ext.Container',
    alias: 'widget.login',
    title: 'Login',
    frame: false,
    align: 'center',
    items: [{
        xtype: 'textfield',
        fieldLabel: 'user',
        allowBlank: false,
        name: 'user'
    },{
        xtype : 'textfield',
        fieldLabel: 'pass',
        allowBlank: false,
        name: 'pass'
    },{
        xtype : 'button',
        text : 'login'
    }]
});
