describe("LoginWindow", function() {
  var loginWindow;

  beforeEach(function() {
    loginWindow = new LoginWindow({tokenUrl:'http://localhost/yase/token.php'});
  }); 

  it("should be able to get a token using a username and a password using a synchronous call",
    function() {
      loginWindow.userName.setValue('pedant.dk');
      loginWindow.password.setValue('test');
      var token;
      loginWindow.on("login", function (msg) {
        token=msg;
      }.bind(token));
      
      loginWindow.loginButton.fireEvent('click');
      expect(token).toBeTruthy();
  });
});
