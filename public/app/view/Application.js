Ext.define('now.view.Application', {
    extend: 'Ext.Container',
    alias: 'widget.application',
    requires: [
        'now.view.Searcher'
    ],
    layout: 'border',
    items: [
        {
            region: 'north',
            xtype: 'toolbar',
            items: [{
                text: 'search'
                }, {
                text:'logout'
            }]
        },
        {
            region: 'center',
            id: 'main',
            layout: {
                type: 'card'
            },
            items: [{
                xtype: 'searcher'
            }
            ]
        }
    ]
});
