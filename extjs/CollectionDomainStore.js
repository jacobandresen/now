/*
 * File: CollectionDomainStore.js
 * Date: Thu Jul 29 2010 22:17:54 GMT+0200 (Rom, sommertid)
 * 
 * This file was generated by Ext Designer version xds-1.0.2.7.
 * http://www.extjs.com/products/designer/
 *
 * This file will be auto-generated each and everytime you export.
 *
 * Do NOT hand edit this file.
 */

CollectionDomainStore = Ext.extend(Ext.data.JsonStore, {
    constructor: function(cfg) {
        cfg = cfg || {};
        CollectionDomainStore.superclass.constructor.call(this, Ext.apply({
            storeId: 'CollectionDomainStore',
            fields: [
                {
                    name: 'domain'
                }
            ]
        }, cfg));
    }
});
new CollectionDomainStore();