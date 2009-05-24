Ext.onReady(function(){
	Ext.state.Manager.setProvider(new Ext.state.CookieProvider());
	
	var store = new Ext.data.SimpleStore({
		store: store,
		columns: []
	})

    var grid = new Ext.grid.GridPanel({
		store:store,
		columns:[
		]
		
	})	
	
})
