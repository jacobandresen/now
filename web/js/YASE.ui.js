YASEUi = Ext.extend(Ext.Viewport, {
  layout: 'border',
  initComponent: function() {
    this.items = [
      {
        xtype: 'tabpanel',
        title: 'Search Engine Administration',
        region: 'center',
        activeTab: 0
      }
    ];
    YASEUi.superclass.initComponent.call(this);
  }
});
