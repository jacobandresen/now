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
 {name: 'iAccountID'}, 
 {name: 'sTablename'}, 
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


