//2011, Jacob Andresen <jacob.andresen@gmail.com>       
Ext.ns('YASE');

YASE.AccountsDataStore = Ext.extend(Ext.data.JsonDataStore, {
    storeId: 'accountsDataStore',
    constructor: function (config) {
        var config = config || {};
        config = Ext.applyIf(config, {
            reader: new Ext.data.JsonReader({
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
            writer: new Ext.data.Writer({
                updateRecord: function (record)  {

                }           
            }),
            proxy: new Ext.data.HttpProxy({
                api {
                    create:'app.php?controller=account&action=create&token=' + YASE.token
                    retrieve:'app.php?controller=account&action=retrieve&token=' + YASE.token
                    update:'app.php?controller=account&action=update&token=' + YASE.token
                    destroy:'app.php?controller=account&action=destroy&token=' + YASE.token
                }
            ])
        }); 
        YASE.AccountsDataStore.superclass.constructor.call(this, config);   
    },
));

YASE.AccountForm = Ext.extend(Ext.form.FormPanel, {
    constructor: function (config) {
        var config = config || {}; 
        config = Ext.applyIf(config, { 
            title: 'account',
            ref: 'account',
            height: 200,
            defaults: {
                anchor: "95%",
                size: 10
            },
            items: [{
                xtype: "textfield", 
                fieldLabel: "Username", 
                name: "username"
            }, {
                xtype: "textfield",
                fieldLabel: "Password",
                name: "password"
            }, { 
                xtype: "textfield", 
                fieldLabel: "First Name", 
                name: "firstName" 
            }, {
                xtype: "textfield",
                fieldLabel: "Last Name",
                name: "lastName"
            }],
            tbar: new Ext.Toolbar({
                width: 600,
                height: 20,
                items: [{
                    text: 'save',
                    handler: function () {
            /*            this.getForm().submit({
                            url:'app.php?controller=account&action=update&token='+YASE.token,
                            waitMsg: 'opdaterer data', 
                            submitEmptyText: false
                        });
                    }.bind(this)*/
                }]
            })
        }); 

        YASE.AccountForm.superclass.constructor.call(this, config);   
    }
});

Ext.reg("accountform", YASE.AccountForm);
