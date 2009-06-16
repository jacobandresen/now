//var App = new Ext.App({});

var settingsProxy = new Ext.data.HttpProxy({
  api: {
    read    : 'app.php/settings/view', 
    create  : 'app.php/settings/create',
    update  : 'app.php/settings/update',
    destroy : 'app.php/settings/destroy'
  }
});  

var settingsReader = new Ext.data.JsonReader({
  successProperty: 'success', 
  idProperty:'iID',
  root:'data'
  }, [
  {name: 'iID'}, 
  {name: 'sName'},
  {name: 'sValue'},
  {name: 'sType'}
]);

var settingsWriter = new Ext.data.JsonWriter({
  returnJson: true,
  writeAllFields: true 
});
 
var settingsStore = new Ext.data.Store({
  id: 'setting',
  proxy: settingsProxy,
  reader: settingsReader,
  writer: settingsWriter,
  autoSave: true,
  listeners: {
    write: function(store, action, result, res, rs ){
      //App.setAlert(res.success, res.message);
     // alert( "write:"+res.message);  
    },
    destroy: function(store, action, result, res, rs ){
      alert( "destroy:"+res.message);
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
});
var textField = new Ext.form.TextField();

var settingsColumns = [
  {header: "name", width:120, sortable: true, 
   dataIndex: 'sName', editor: textField},
  {header: "value", width:120, sortable: true,
   dataIndex: 'sValue', editor:textField}
]; 

Ext.onReady(function() {
  Ext.QuickTips.init();

  var settingsGrid = new YASE.SettingsGrid({
    renderTo: 'crawler-grid',
    store: settingsStore,
    columns: settingsColumns, 
    /*listeners: {
     rowclick: function(g, index, ev) {
       var rec = g.store.getAt(index);
       //alert('click');  
     },
     destroy: function() {
       alert('destroy'); 
       //settingsForm.getForm().reset();
     }
   }*/
 });
  settingsStore.load();
});


