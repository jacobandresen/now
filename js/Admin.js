var App = new Ext.App({});

var settingsProxy = new Ext.data.HttpProxy({
  api: {
      read    : 'app.php/settings/view', 
      create  : 'app.php/settings/create',
      update  : 'app.php/settings/update',
      destroy : 'app.php/settings/destroy'
  }
});  

var settingsReader = new Ext.data.JsonReader({
  totalProperty: 'total',
  successProperty: 'success',
  idProperty: 'id',
  root: 'setting'
  }, [
  {name: 'iID'}, 
  {name: 'sName', allowBlank:false},
  {name: 'sValue', allowBlank:false},
  {name: 'sType'}
]);

var settingsWriter = new Ext.data.JsonWriter({
  returnJson: true,
  writeAllFields: false
});
 
var settingsStore = new Ext.data.Store({
  id: 'setting',
  proxy: settingsProxy,
  reader: settingsReader,
  writer: settingsWriter,
  autoSave: true,
  listeners: {
    write: function(store, action, result, res, rs ){
      App.setAlert(res.success, res.message);
    },
    exception: function(proxy, type, action, options, res, arg){
      if (type === 'remote') {
        Ext.Msg.show({
          title: 'REMOTE EXCEPTION',
          msg: res.message,
          icon: Ext.MessageBox.ERROR
        });
      }
    }
  }
}
var textField = new Ext.form.TextField();

var settingsColumns = [
  {header: "name", width:120, sortable: true, 
   dataIndex: 'sName', editor: textField},
  {header: "value", width:120, sortable: true,
   dataIndex: 'sValue', editor:textField}
]; 
settingsStore.load();

Ext.onReady(function() {
   Ext.QuickTips.init();
   var settingsGrid = new YASE.SettingsGrid({
     renderTo: 'crawler-settings',
     store: settingsStore,
     columns: settingsColumns,
     listeners: {
       rowclick: function(g, index, ev) {
         var rec = g.store.getAt(index);
       },
       destroy: function() {
       //  settingsForm.getForm().reset();
       }
     }
   });
});


