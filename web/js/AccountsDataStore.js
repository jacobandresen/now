//2011, Jacob Andresen <jacob.andresen@gmail.com>       
Ext.namespace('YASE');

YASE.AccountRecord = Ext.data.Record.create([
   'username',
   'password',
   'firstName',
   'lastName' 
]);

YASE.AccountsDataStore = Ext.extend(Ext.data.JsonStore, {
    constructor: function (config) {
        var config = config || {};
        config = Ext.applyIf(config, {
            storeId: 'accountsDataStore',
            autoLoad: false, 
            autoSave: false,
            batch: false,
            writer: new Ext.data.JsonWriter({
                render: function (params, baseParams, data) {
                    params = Ext.apply(params,baseParams); 
                    params.json = Ext.encode(data);
                }
            }),
            reader: new Ext.data.JsonReader({
                    root:'account',
                    id:'id',
                    succes:'success' ,
                    fields: YASE.AccountRecord 
                   }),
            proxy: new Ext.data.HttpProxy({
                url:'/yase/app.php?controller=account' 
            })
        }); 
        YASE.AccountsDataStore.superclass.constructor.call(this, config);   

        this.on("beforesave", function (store, record ){
            store.baseParams.action="create";
        });
   }
});

var accountsDataStore = new YASE.AccountsDataStore();
