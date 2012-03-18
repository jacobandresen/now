Ext.define('now.view.Viewport', {
    extend: 'Ext.container.Viewport',
    alias: 'widget.viewport', 
    requires: [
        'now.view.Login',
        'now.view.Application'
    ],
    layout: 'card',
    items: [
        {
            xtype: 'login'
        },
        {
            xtype: 'application'
        }
    ]
});
