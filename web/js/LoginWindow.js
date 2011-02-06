Ext.namespace('YASE');

YASE.LoginWindow = Ext.extend(Ext.Window, {
  
    title: 'Login',
    width: 220,
    height: 160,
    layout: 'absolute',
    closable: false,

    initComponent: function(config) {
        this.items = [
        {
            xtype: 'textfield',
            width: 120,
            name: 'userName',
            x: 60,
            y: 20,
            ref: 'userName'
        },
        {
            xtype: 'textfield',
            width: 120,
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
            text: 'Login',
            x: 110,
            y: 100,
            ref: 'loginButton'
        }
        ];
        LoginWindow.superclass.initComponent.call(this);
        this.addEvents(['login']);
    },

    initEvents: function ()  {
        LoginWindow.supperclass.initEvents.call(this);
        this.loginButton.on('click', this.getToken, this);
    },

    getToken: function () {
        request = new Ajax.Request(
          this.tokenUrl,
        {
            parameters:{username:this.userName.getValue(),
            password:this.password.getValue()},
            asynchronous: false,
            onSuccess: function (msg) {
                var token = msg.responseText;
                this.token = token;
                if (token !== '') {
                    this.fireEvent('login', token);
                } else {
                    Ext.MessageBox.alert('Login failed');
                }
            }.bind(this)
      });
    }
});
