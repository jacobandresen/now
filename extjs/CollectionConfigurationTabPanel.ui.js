/*
 * File: CollectionConfigurationTabPanel.ui.js
 * Date: Fri Jul 23 2010 18:55:31 GMT+0200 (Rom, sommertid)
 * 
 * This file was generated by Ext Designer version xds-1.0.2.7.
 * http://www.extjs.com/products/designer/
 *
 * This file will be auto-generated each and everytime you export.
 *
 * Do NOT hand edit this file.
 */

CollectionConfigurationTabPanelUi = Ext.extend(Ext.TabPanel, {
    activeTab: 0,
    height: 739,
    initComponent: function() {
        this.items = [
            {
                xtype: 'accountpanel'
            },
            {
                height: 427,
                width: 1220,
                xtype: 'crawlerpanel'
            },
            {
                xtype: 'indexerpanel'
            }
        ];
        CollectionConfigurationTabPanelUi.superclass.initComponent.call(this);
    }
});
