Ext.require([
    'Ext.data.*',
    'Ext.form.*'
]);

Ext.onReady(function(){
    Ext.define("Result", {
        extend: 'Ext.data.Model',
        fields: [
            'id',
            'rank',
            'url',
            'title',
            'fragment'
        ]
    })

    ds = Ext.create('Ext.data.Store', {
        model: 'Result',
        proxy: {
            type: 'jsonp',
            url: 'ext4.1-api-search.php',
            reader: {
                type: 'json',
                root: 'data'
            }
        },
    });

    panel = Ext.create('Ext.panel.Panel', {
        renderTo: Ext.getBody(),
        title: 'Search the Ext JS 4.1 API docs',
        width: 600,
        bodyPadding: 10,
        layout: 'anchor',

        items: [{
            xtype: 'combo',
            store: ds,
            displayField: 'title',
            typeAhead: false,
            hideLabel: true,
            hideTrigger:true,
            anchor: '100%',

            listConfig: {
                loadingText: 'Searching...',
                emptyText: 'No matching posts found.',

                getInnerTpl: function() {
                    return '<a class="search-item" href="{url}">' +
                        '<h3><span>{content}<br /></span>{title}</h3>' +
                        '{fragment}' +
                    '</a>';
                }
            }
        }, {
            xtype: 'component',
            style: 'margin-top:10px',
            html: 'Live search requires a minimum of 4 characters.'
        }]
    });
});
