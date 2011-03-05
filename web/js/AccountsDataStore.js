//2011, Jacob Andresen <jacob.andresen@gmail.com>       
Ext.namespace('YASE');

YASE.AccountsDataStore = Ext.extend(Ext.data.JsonStore, {
    constructor: function (config) {
        var config = config || {};
        config = Ext.applyIf(config, {
            storeId: 'accountsDataStore',
            autoLoad: false, 
            writer: new Ext.data.JsonWriter({}),
            reader: new Ext.data.JsonReader({
                autoLoad: false, 
                root:'account',
                id:'id',
                succes:'success', 
                items:[
                    'username',
                    'password',
                    'firstName',
                    'lastName' 
                ]
            }),
            proxy: new Ext.data.HttpProxy({
                url:'app.php?controller=account'
            })
        }); 
        YASE.AccountsDataStore.superclass.constructor.call(this, config);   
   }
});

var accountsDataStore = new YASE.AccountsDataStore();

