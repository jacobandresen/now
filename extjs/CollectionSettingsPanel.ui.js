/*
 * File: CollectionSettingsPanel.ui.js
 * Date: Thu Jul 29 2010 22:12:00 GMT+0200 (Rom, sommertid)
 * 
 * This file was generated by Ext Designer version xds-1.0.2.7.
 * http://www.extjs.com/products/designer/
 *
 * This file will be auto-generated each and everytime you export.
 *
 * Do NOT hand edit this file.
 */

CollectionSettingsPanelUi = Ext.extend(Ext.Panel, {
    title: 'Collection settings',
    height: 400,
    width: 689,
    initComponent: function() {
        this.items = [
            {
                xtype: 'label',
                text: 'Name',
                autoWidth: true
            },
            {
                xtype: 'textfield',
                width: 85,
                autoWidth: true,
                id: 'Name'
            },
            {
                xtype: 'spacer'
            },
            {
                xtype: 'label',
                text: 'Page Limit',
                autoWidth: true
            },
            {
                xtype: 'numberfield',
                width: 39,
                id: 'pageLimit'
            },
            {
                xtype: 'spacer'
            },
            {
                xtype: 'label',
                text: 'Level Limit'
            },
            {
                xtype: 'numberfield',
                width: 39,
                id: 'levelLimit'
            },
            {
                xtype: 'collectiondomaingridpanel'
            }
        ];
        CollectionSettingsPanelUi.superclass.initComponent.call(this);
    }
});
