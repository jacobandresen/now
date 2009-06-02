Ext.onReady(function(){

  //TODO: handle login
  //TODO: create a component for this code (maybe extend on EditorGridPanel ? )

  //TODO: initialize with an available account to the user
  //HACK: for now I just list "pedant.dk" from the sample setup
  var iAccount=12;

  var settingsModel;
  var filterGrid;

  //general
  Ext.QuickTips.init();	
  Ext.state.Manager.setProvider(new Ext.state.CookieProvider());
  

  //BEGIN: combo box selection
  var Account = Ext.data.Record.create([
    {name: 'id'},
    {name: 'user_id'},  
    {name: 'name'},
    {name: 'level_imit'},
    {name: 'crawl_limit'}
  ]);

  var accountStore = new Ext.data.Store({
   url: 'services/account.php',
   reader: new Ext.data.JsonReader({ id: 'name'}, Account)
  });

  var accountCombo = new Ext.form.ComboBox({
   store: accountStore,
   displayField: 'name',
   mode: 'local',
   triggerAction: 'all',
   selectOnFocus:true, 
   emptyText: 'Select an account ...',
   applyTo: 'account-combo',
   listeners: {
     select: function(combo, record) {
        iAccount = record.get('id');
        filterGrid.title = record.get('name'); 
        filterStore.proxy.conn.url = 'services/setting.php?action=GET&account='+iAccount+'&table=filters';
        filterStore.load(); 
      }    
   } 
 });
 accountStore.load();
//END: combo box selection

//BEGIN:crawler filter settings
  var Setting = Ext.data.Record.create([
    {name: 'id'}, 
    {name: 'name'},
    {name: 'value'},
    {name: 'type'}
  ]);

  //TODO: when iAccount changes then the content of this grid should change 
  var filterStore = new Ext.data.Store({
    proxy: new Ext.data.HttpProxy({url: 'services/setting.php?action=GET&account='+iAccount+'&table=filters'}),
    reader: new Ext.data.JsonReader({ id:'name' },Setting),
    sortInfo:{field:'name',direction:'ASC'},
    listeners: {
      update: function(store,r, oper) {
        console.log('update!'); 
      },
      save: function(store, r, oper) {
        console.log('save!');
      } 
    }
  });
  
  settingsModel = new Ext.grid.ColumnModel([
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

  filterGrid = new Ext.grid.EditorGridPanel({
    store:filterStore,
    cm: settingsModel,
    renderTo: 'crawler-grid',
    width: 600,
    height:300,
    title:'crawler skip filters',
    tbar: [
      {
      tooltip: 'save', 
      iconCls: 'save', 
      width: 100, 
       handler: function() {
             //TODO: get the edited fields and save them to the server  
	     console.log("SAVE!");
            } 
      },
      {
      tooltip: 'add filter',
      iconCls: 'add', 
      width: 100, 
      handler: function() {
                var s=new Setting({
		   name:'',
                   value:'',
                   type: ''
                }); 
                filterGrid.stopEditing();
                filterStore.insert(0,s);
	        filterGrid.startEditing(0,0);
              } 
       }]
   });	
   
   filterStore.load();
//END:crawler filter settings

})
