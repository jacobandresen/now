/*
 * File: CollectionDomainGridPanel.ui.js
 * Date: Sun Sep 12 2010 08:33:48 GMT+0200 (CEST)
 * 
 * This file was generated by Ext Designer version xds-1.0.2.14.
 * http://www.extjs.com/products/designer/
 *
 * This file will be auto-generated each and everytime you export.
 *
 * Do NOT hand edit this file.
 */

CollectionDomainGridPanelUi = Ext.extend(Ext.grid.GridPanel, {
    title: 'domains',
    store: 'DomainStore',
    height: 300,
    width: 800,
    initComponent: function() {
        this.columns = [
            {
                xtype: 'gridcolumn',
                dataIndex: 'string',
                header: 'domain',
                sortable: true,
                width: 100
            }
        ];
        this.tbar = {
            xtype: 'toolbar',
            id: 'AccountToolbar',
            items: [
                {
                    xtype: 'button',
                    text: 'Add',
                    ref: '../AddDomainButton'
                },
                {
                    xtype: 'button',
                    text: 'Save',
                    ref: '../SaveDomainButton'
                },
                {
                    xtype: 'button',
                    text: 'Delete'
                }
            ]
        };
        CollectionDomainGridPanelUi.superclass.initComponent.call(this);
    }
});
