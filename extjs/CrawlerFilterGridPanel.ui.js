/*
 * File: CrawlerFilterGridPanel.ui.js
 * Date: Sun Sep 12 2010 08:33:48 GMT+0200 (CEST)
 * 
 * This file was generated by Ext Designer version xds-1.0.2.14.
 * http://www.extjs.com/products/designer/
 *
 * This file will be auto-generated each and everytime you export.
 *
 * Do NOT hand edit this file.
 */

CrawlerFilterGridPanelUi = Ext.extend(Ext.grid.GridPanel, {
    title: 'Crawler Filter',
    store: 'CrawlerFilterStore',
    height: 400,
    width: 800,
    initComponent: function() {
        this.columns = [
            {
                xtype: 'gridcolumn',
                dataIndex: 'number',
                header: 'domain',
                sortable: true,
                width: 100,
                align: 'right'
            },
            {
                xtype: 'gridcolumn',
                dataIndex: 'string',
                header: 'URL regex',
                sortable: true,
                width: 100
            }
        ];
        this.tbar = {
            xtype: 'crawlerfiltertoolbar'
        };
        CrawlerFilterGridPanelUi.superclass.initComponent.call(this);
    }
});
