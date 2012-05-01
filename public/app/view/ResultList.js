Ext.define('now.view.ResultList', {
    extend: 'Ext.grid.Panel',
    requires: ['now.store.Result'],
    alias: 'widget.resultlist',
    store: Ext.create('now.store.Result'),
    columns: [
        { text: 'rank', flex:0, dataIndex: 'rank', hidden: true},
        { text: 'title',  flex:0, dataIndex: 'title', size: 12},
        { text: 'text',  flex:1, dataIndex: 'fragment'}
    ]
});
