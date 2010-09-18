/*
 * File: LoginWindow.ui.js
 * Date: Sat Sep 18 2010 20:34:24 GMT+0200 (CEST)
 * 
 * This file was generated by Ext Designer version xds-1.0.2.14.
 * http://www.extjs.com/products/designer/
 *
 * This file will be auto-generated each and everytime you export.
 *
 * Do NOT hand edit this file.
 */

LoginWindowUi = Ext.extend(Ext.Window, {
  title: 'Login',
  width: 220,
  height: 163,
  layout: 'absolute',
  closable: false,
  initComponent: function() {
    this.items = [
      {
        xtype: 'textfield',
        width: 130,
        name: 'userName',
        x: 60,
        y: 20,
        ref: 'userName'
      },
      {
        xtype: 'textfield',
        name: 'password',
        x: 60,
        y: 60,
        inputType: 'password',
        ref: 'password'
      },
      {
        xtype: 'label',
        text: 'username',
        x: 0,
        y: 20
      },
      {
        xtype: 'label',
        text: 'password',
        x: 0,
        y: 60
      },
      {
        xtype: 'button',
        text: 'LoginButton',
        x: 110,
        y: 100,
        ref: 'loginButton'
      }
    ];
    LoginWindowUi.superclass.initComponent.call(this);
  }
});
