Ext.namespace('YASE');


YASE.SettingsGrid = Ext.extend(Ext.grid.EditorGridPanel, {
  iconCls: 'silk-grid',
  frame: true,
  title: 'Settings',
  autoScroll: true,
  height: 300,
  style: 'margin-top: 10px',

  initComponent : function() {
    this.viewConfig = {
      forceFit: true
    };
    this.relayEvents(this.store, ['destroy', 'save', 'update'];
    this.tbar = this.buildTopToolbar();
    this.buttons = this.buildUI();

    YASE.SettingsGrid.initComponent.call(this);
  },

  buildTopToolBar : function () {
    return [{
      text: 'Add',
      iconCls: 'silk-add',  
      handler: this.onAdd,
      scope: this
    }, '-' , {
      text: 'Delete',
      iconCls: 'silk-delete',
      handler: this.onDelete,
      scope: this 
    }, '-'] ;
  },

  buildUI : function () {
    return [{
      text: 'Save',
      iconCls: 'icon-save',
      handler: this.onSave,
      scope: this
    }];
  },

  onSave: function (btn, ev) {
    this.store.save();
  },
  onAdd: function (btn, ev) {
    var s = new this.store.recordType({
       iID: '',
       sName: '',
       sValue: '',
       sType: '' 
    });
    this.stopEditing();
    this.store.insert(0, s);
    this.startEditing(0,1); 
  },
  onDelete : function(btn, ev) {
    var index = this.getSelectionModel().getSelectedCell();
    if (!index) {
      return false;
    }
    var rec = this.store.getAt(index[0]);
    this.store.remove(rec);
  } 
});

