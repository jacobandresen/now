/*
 * File: ConfigurationTabPanel.ui.js
 * Date: Thu Jul 29 2010 22:17:54 GMT+0200 (Rom, sommertid)
 * 
 * This file was generated by Ext Designer version xds-1.0.2.7.
 * http://www.extjs.com/products/designer/
 *
 * This file will be auto-generated each and everytime you export.
 *
 * Do NOT hand edit this file.
 */

ConfigurationTabPanelUi = Ext.extend(Ext.TabPanel, {
    activeTab: 1,
    height: 400,
    width: 800,
    initComponent: function() {
        this.items = [
            {
                height: 400,
                width: 800,
                xtype: 'collectionsettingspanel'
            },
            {
                xtype: 'crawlerfiltergridpanel'
            },
            {
                xtype: 'indexerfiltergridpanel'
            },
            {
                xtype: 'panel',
                title: 'View Log',
                disabled: true,
                height: 400,
                width: 800
            },
            {
                xtype: 'panel',
                title: 'Search Test',
                disabled: true,
                height: 400,
                width: 800
            }
        ];
        ConfigurationTabPanelUi.superclass.initComponent.call(this);
    }
});
