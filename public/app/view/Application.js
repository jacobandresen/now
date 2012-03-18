Ext.define('now.view.Application', {
    extend: 'Ext.Container',
    alias: 'widget.application',
    requires: [
        'now.view.Searcher',
    ],
    layout: 'card',
    items: [
        {
            xtype: 'searcher'
        }
    ]
});
