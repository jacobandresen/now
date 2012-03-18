Ext.namespace('now');

Ext.application({
    name: 'now',
    controllers: ['Searcher'],
    requires: [
        'now.view.Searcher'
    ],
    launch: function () {
        Ext.create('now.view.Searcher',
            {renderTo: Ext.getBody()}
        );
    }
});
