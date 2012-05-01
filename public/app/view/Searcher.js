Ext.define('now.view.Searcher', {
    extend: 'Ext.Container',
    alias: 'widget.searcher',
    requires: [
        'Ext.form.Field',
        'Ext.Button',
        'now.view.ResultList'
        ],

    layout: { type: 'vbox' },
    width: 800,
    items: [
    {
        height: '25%',
        layout: {
            type:   'hbox'
        },
        items: [{
            xtype:  'textfield',
            width:  500
        },{
            xtype:  'button',
            text:   'search'
        }]
    },
    {
       height: '75%',
       xtype: 'resultlist'
    }]
});
