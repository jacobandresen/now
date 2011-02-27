//2011, Jacob Andresen <jacob.andresen@gmail.com>       
Ext.namespace('YASE');

YASE.AccountsDataStore = Ext.extend(Ext.data.JsonStore, {
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
            proxy: new Ext.data.HttpProxy({
                api:{
                    load:'app.php?controller=account&action=retrieve&token=' + token,
                    create:'app.php?controller=account&action=create&token=' + token,
                    update:'app.php?controller=account&action=update&token=' + token,
                    destroy:'app.php?controller=account&action=destroy&token=' + token
                }
            })
        }); 
        YASE.AccountsDataStore.superclass.constructor.call(this, config);   
    }
});

YASE.AccountForm = Ext.extend(Ext.form.FormPanel, {
    constructor: function (config) {
        var config = config || {}; 
        config = Ext.applyIf(config, { 
            store: new YASE.AccountsDataStore({}),
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
                    text: 'save'//,
                    //handler: this.onSave.CreateDelegate( this,  [ ])
               }]
            })
        }); 

        YASE.AccountForm.superclass.constructor.call(this, config);  
     //   this.onLoad(); 
    }, 

    onSave: function () {
        console.log("clicked save");
        console.log(this);
    },

    onLoad: function () {
        console.log("on load");
    }

});

Ext.reg("accountform", YASE.AccountForm);
