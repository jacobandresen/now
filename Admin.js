Ext.onReady(function(){
  Ext.QuickTips.init();	
  Ext.state.Manager.setProvider(new Ext.state.CookieProvider());
	
  var cm = new Ext.grid.ColumnModel([{
    id:'regex',
    header: 'regular expression',
    dataIndex:'regex',
    width:220,
    editor: new Ext.form.TextField({ allowBlank:false })
   }]);
  cm.defaultSortable = true;
	
  var Setting = Ext.data.Record.create([
    {name: 'name', type: 'string'},
    {value: 'value', type: 'string'}
  ]);

  var store = new Ext.data.Store({
    proxy: new Ext.data.ScriptTagProxy({ url: 'services/crawlersettings.php'}),
    reader: new Ext.data.XmlReader({
             record: ''
           }, Setting),
    sortInfo:{field:'name', direction:'ASC'}
  });

  var grid = new Ext.grid.EditorGridPanel({
    store:store,
    cm: cm,
    renderTo: 'crawler-grid',
    width: 600,
    height:300,
    title:'crawler settings',
    tbar: [{
      text: 'add settings',
      handler: function() {
      var s = new Setting({
        name:'',
        regex:''
      });
      store.insert(0,s);
      }		
    }]
		
   })	
    store.load();
})
