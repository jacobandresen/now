<?php
 function grid($model, $div, $jsonDef) {
  ?>
<script type="text/javascript">
var <?= $model; ?>Store = new Ext.data.Store({
  id: '<?= $model;?> setting',
  proxy: new Ext.data.HttpProxy({
  api: {
    read    : 'app.php/<?=$model;?>/view', 
    create  : 'app.php/<?=$model;?>/create',
    update  : 'app.php/<?=$model;?>/update',
    destroy : 'app.php/<?=$model;?>/destroy'
  }
  }) , 
  reader: new Ext.data.JsonReader({
    successProperty: 'success', 
    idProperty:'iID',
    root:'data'
    }, [
    {name: 'iID'}, 
    {name: 'sName'},
    {name: 'sValue'},
    {name: 'sType'}
    ]),
  writer: new Ext.data.JsonWriter({
    returnJson: true,
    writeAllFields: true 
  }),
  autoSave: true
});

var <?=$model;?>Columns =  [
   {header: "name", width:120, sortable:true,
     dataIndex:'sName', editor:new Ext.form.TextField()},
   {header: "value", width:120, sortable:true,
     dataIndex: 'sValue', editor:new Ext.form.TextField()}
  ];

Ext.onReady( function(){
   <?=$model;?>Store.load();
});

var <?=$model."Grid" ;?> = Ext.extend(Ext.grid.EditorGridPanel, {
    renderTo: '<?php print $div ; ?>' ,
    iconCls: 'silk-grid',
    frame: true,
    height: 300, 
    title: '<?=$model;?> Settings',
    initComponent : function() { 
      this.viewConfig = { 
        forceFit: true 
     };
    this.relayEvents(this.store, ['write','destroy', 'save', 'update']);
    this.tbar = this.buildTopToolBar();
    this.buttons = this.buildUI();
    <?=$model."Grid";?>.superclass.initComponent.call(this); 
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
      handler: this.onDelete,
      scope: this
    }
   ];
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
   var <?=$model;?> = 
     new <?=$model."Grid";?>( {
      renderTo: '<?=$div;?>',
      store: <?=$model;?>Store,
     columns: <?=$model;?>Columns
   });
 });
</script>

<?php
}
?>
