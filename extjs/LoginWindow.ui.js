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
