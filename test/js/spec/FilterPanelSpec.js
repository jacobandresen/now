describe("FilterPanel", function() {

  var testAccount="pedant.dk";
  var testPassword="test";

  beforeEach(function() {
  }); 

  it("should be able to retrieve test filters from the test account",
    function() {
      var numberOfFilters=0;
      expect(numberOfFilters).toBeGreaterThan(0);
    }
  );

  it("should be able to add a filter to the grid on screen", 
    function () {
     expect(true).toBeFalsy();
    }
  );
  
  it("should be able to find the altered filters in the grid",
    function () {
      expect(true).toBeFalsy();
    }
  );

  it("should be able to save the altered filters to the server",
    function () {
      expect(true).toBeFalsy();
    } 
   ); 
});
