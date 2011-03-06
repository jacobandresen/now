describe("AccountsDataStore", function() {

    beforeEach(function() {
    }); 

    it("should be able to save account details",
        function() {
            var savedAccountDetails = false;
            var accountsDataStore = new YASE.AccountsDataStore();
            console.log(accountsDataStore); 
            var accountRecord = new YASE.AccountRecord({
                firstName:'Jacob',
                lastName:'Andresen', 
                password:'test'
                });
            accountsDataStore.insert(0, accountRecord);
            accountsDataStore.save();
            saveAccountDetails = true;
            expect(savedAccountDetails).toBeTruthy();
    });

/*
    it("should be able to read account details",
        function() {
            var readAccountDetails = false; 
            expect(readAccountDetails).toBeTruthy();
    });

    it("should be able to delete account details",
        function() {
            var deletedAccountDetails = false;
            expect(deletedAccountDetails).toBeTruthy();
    });
*/

});
