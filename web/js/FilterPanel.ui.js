FilterPanelUi = Ext.extend(Ext.grid.EditorGridPanel, {
  title: 'filters',
  width: 400,
  height: 250,
  initComponent: function () {
    this.columns = [
      { 
        xtype: 'gridcolumn', 
        header: 'domain',
        sortable: true,
        resizable: true,
        width: 100,
        dataIndex: 'domain'
      } 
    ];
  }
});
