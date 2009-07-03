<?php
 function settingGrid($table, $filter, $div) {
 ?>
<script type="text/javascript">
var <?php print $table.$filter."Grid" ;?> = Ext.extend(Ext.grid.EditorGridPanel, {
    renderTo: '<?php print $div ; ?>' ,
    iconCls: 'silk-grid',
    frame: true,
    title: '<?php print $table;?> Settings',
    height:  300,

    initComponent : function() { 
      this.viewConfig = { 
        forceFit: true 
     };
    this.relayEvents(this.store, ['destroy', 'save', 'update']);
    this.tbar = this.buildTopToolBar();
    this.buttons = this.buildUI();
    <?php print $table.$filter."Grid";?>.superclass.initComponent.call(this); 
  },

  buildTopToolBar : function () {
    return [{
      text: 'Add',
      iconCls: 'silk-add',
      handler: this.onAdd,
      scope: this
    }, '-', {
      text: 'Delete',
      iconCls: 'icon-save',
      handler: this.onSave,
      scope: this
    }];
  },

  buildUI: function () {
    return [{
       text: 'Save',
       iconCls: 'icon-save', 
       handler: this.onSave,
       scope: this
     }];
   },


  onSave: function(btn, ev) {
   this.store.save();
  },
  
  onAdd : function(btn, ev) {
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


Ext.onReady(function() {
   var <?php print $table.$filter;?> = 
     new <?php print $table.$filter."Grid";?>( {
      renderTo: '<?php print $div;?>',
      store: settingsStore,
     columns: settingsColumns
   });
   settingsStore.load();
 });

</script>

<?php
}
?>



