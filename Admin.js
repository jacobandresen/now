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
  
  var Account = Ext.data.Record.create([
    {name: 'iID'},
    {name: 'iAccountID'},  
    {name: 'sName'},
    {name: 'iLevelLimit'},
    {name: 'iCrawlLimit'}
  ]);

  var accountStore = new Ext.data.Store({
    url: 'app.php/accounts', 
    reader: new Ext.data.JsonReader({ iID: 'sName'}, Account)
  });

  var accountCombo = new Ext.form.ComboBox({
   store: accountStore,
   displayField: 'sName',
   mode: 'local',
   triggerAction: 'all',
   selectOnFocus:true, 
   emptyText: 'Select an account ...',
   applyTo: 'account-combo',
   listeners: {
     select: function(combo, record) {
        iAccount = record.get('iID');
        filterGrid.title = record.get('sName');   
        filterStore.proxy.conn.url = 'app.php/settings?account_id='+iAccount;
        console.log(filterStore.proxy.conn.url); 
        filterStore.load(); 
      }    
   } 
 });
 accountStore.load();

  var Setting = Ext.data.Record.create([
    {name: 'iID'}, 
    {name: 'sName'},
    {name: 'sValue'},
    {name: 'sType'}
  ]);

  var filterStore = new Ext.data.Store({
    proxy: new Ext.data.HttpProxy({url: 'app.php/settings'}),
    reader: new Ext.data.JsonReader({ id:'sName' },Setting),
    sortInfo:{field:'sName',direction:'ASC'},
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
    dataIndex:'sName',
    width:220,
    editor: new Ext.form.TextField({ allowBlank:false })
   },
   {
    id:'value',
    header: 'value',
    dataIndex: 'sValue',
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
