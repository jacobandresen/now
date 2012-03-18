Ext.define('now.view.Searcher', {
    extend: 'Ext.Container',
    alias: 'widget.searcher',
    requires: [ 'now.view.ResultList' ],

    layout: { type: 'vbox' },
    items: [
    {
        xtype: 'container',
        height: '10%',
        layout: {
            type:   'hbox'
        },
        items: [{
            xtype:  'textfield',
            width:  '80%'
        },{
            xtype:  'button',
            text:   'search',
            width:  '20%'
        }]
    },
    {
       height: '90%',
       xtype: 'resultlist'
    }]
});
