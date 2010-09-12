/*
 * File: CollectionSettingsPanel.ui.js
 * Date: Sun Sep 12 2010 08:33:48 GMT+0200 (CEST)
 * 
 * This file was generated by Ext Designer version xds-1.0.2.14.
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
                text: 'Start URL',
                autoWidth: true
            },
            {
                xtype: 'textfield',
                width: 85,
                autoWidth: true,
                id: 'StartURL'
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
