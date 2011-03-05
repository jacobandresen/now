describe("AccountsDataStore", function() {

    var accountsDataStore = new YASE.AccountsDataStore();

    beforeEach(function() {
    }); 

    it("should be able to read account details",
        function() {
            var readAccountDetails = false; 
            expect(readAccountDetails).toBeTruthy();
    });

    it("should be able to save account details",
        function() {
            var savedAccountDetails = false;
            expect(savedAccountDetails).toBeTruthy();
    });

    it("should be able to delete account details",
        function() {
            var deletedAccountDetails = false;
            expect(deletedAccountDetails).toBeTruthy();
    });

});
