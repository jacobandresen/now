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
            batch: false,
            writer: new Ext.data.JsonWriter({
                updateRecord: function (record) {

                });
            }),
            reader: new Ext.data.JsonReader({
                    root:'account',
                    id:'id',
                    succes:'success' ,
                    fields: YASE.AccountRecord 
                   }),
            proxy: new Ext.data.HttpProxy({
                url:'/yase/app.php?controller=account' //TODO: configure base url
            })
        }); 
        YASE.AccountsDataStore.superclass.constructor.call(this, config);   

        this.on("beforesave", function (store, record ){
            //TODO: find out if we are doing a create, retrieve, update or destroy 
            store.baseParams.action="save";
            console.log(store.data.items[0].data);
            store.baseParams.json=Object.toJSON(store.data.items[0].data);
            //console.log(record);
        });
   }
});

var accountsDataStore = new YASE.AccountsDataStore();
