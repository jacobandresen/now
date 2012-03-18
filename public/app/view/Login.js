Ext.define('now.view.Login', {
    extend: 'Ext.Container'
    alias: 'widget.login',
    title: 'Login',
    items = [{
        xtype: 'textfield',
        fieldLabel: 'user',
        allowBlank: false
    },{
        xtype: 'passwordfield',
        fieldLabel: 'pass'
        allowBlank: false
    }];
});
