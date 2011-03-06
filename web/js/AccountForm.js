//2011, Jacob Andresen <jacob.andresen@gmail.com>       
Ext.namespace('YASE');

YASE.AccountForm = Ext.extend(Ext.form.FormPanel, {
    constructor: function (config) {
        var me = this; 
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
                xtype: "hidden",
                name: "AccountIdentifier",
                ref: "AccountIdentifier"
            },
            {
                xtype: "textfield", 
                fieldLabel: "Username", 
                name: "username"
            }, {
                xtype: "textfield", 
                fieldLabel: "Password",
                name: "password",
                inputType: "password"
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
                    handler: this.onSave.createDelegate( this,  [ ])
               }]
            })
        }); 

        YASE.AccountForm.superclass.constructor.call(this, config);  
    }, 

    onSave: function () {
        console.log("clicked save");
        accountsDataStore.add( new accountsDataStore.recordType({firstName:'Jacob', lastName:'Andresen'}, Ext.id()));
        accountsDataStore.save();
    },

    onLoad: function () {
        accountsDataStore.baseParams.controller="account";
        accountsDataStore.baseParams.action="read"; 
        accountsDataStore.baseParams.json =Object.toJSON({id:yase.accountId});
        accountsDataStore.load();
    }

});

Ext.reg("accountform", YASE.AccountForm);
