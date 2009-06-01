Ext.onReady(function(){

  //TODO: login
  Ext.QuickTips.init();	
  Ext.state.Manager.setProvider(new Ext.state.CookieProvider());

  var Setting = Ext.data.Record.create([
    {name: 'name'},
    {name: 'value', type: 'string'}
  ]);

  var filterStore = new Ext.data.Store({
    proxy: new Ext.data.HttpProxy({ 
	url: 'services/setting.php?action=GET&table=filters'}),
        reader: new Ext.data.JsonReader({
            root: 'field',id:'id' 
            },Setting),
         sortInfo:{field:'name',direction:'ASC'},
         listeners: {
             update: function(store,r, oper) {
               //TODO: open a Ext.data.connection to "services/setting.php" 
               // and save the edited fields to the server 
               console.log('hello world'); 
              } 
          }
  });
  
  var settingsModel = new Ext.grid.ColumnModel([
   {
    id:'name',
    header: 'name',
    dataIndex:'name',
    width:220,
    editor: new Ext.form.TextField({ allowBlank:false })
   },
   {
    id:'value',
    header: 'value',
    dataIndex: 'value',
    width:220,
    editor: new Ext.form.TextField({ allowBlank:false })
   }
   ]);
  settingsModel.defaultSortable = true;

  var filterGrid = new Ext.grid.EditorGridPanel({
    store:filterStore,
    cm: settingsModel,
    renderTo: 'crawler-grid',
    width: 600,
    height:300,
    title:'crawler settings',
    tbar: [
      {
      text: 'save',
      handler: function() {
             //TODO: get the edited fields and save them to the server  
	     console.log("SAVE!");
            } 
      },
      {
      text: 'add filter',
      handler: function() {
                var s=new Setting({
		   name:'',
                   value:'',
                   type: ''
                }); 
                filterGrid.getStore().insert(0,s);
                filterGrid.stopEditing();
                filterStore.insert(0,s);
	        filterGrid.startEditing(0,0);
              } 
       }]
   });	
   
   filterStore.load();
})
