describe("LoginWindow", function() {
    var loginWindow;
    var tokenUrl = 'http://localhost/yase/token.php';

    beforeEach(function() {
        loginWindow = new LoginWindow({tokenUrl:tokenUrl});
    }); 

    it("should be able to get a token using a username and a password using a synchronous call",
        function() {
            loginWindow.userName.setValue('pedant.dk');
            loginWindow.password.setValue('test');
            var token;
        
            loginWindow.on("login", function (msg) {
                token=msg;
                expect(token).toBeTruthy();
            });
        
            loginWindow.loginButton.fireEvent('click');
    });
});
