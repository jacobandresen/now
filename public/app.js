Ext.application({
    name: 'now',
    controllers: ['Application', 'Searcher'],
    requires: [
        'now.view.Viewport'
    ],
    launch: function () {
        Ext.create('now.view.Viewport',
            {renderTo: Ext.getBody()}
        );
    }
});
