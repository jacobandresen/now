function SkipTable (sName, idDiv) {
  this.sName=sName; 
  this.aData=Array(); //string array
};

SkipTable.prototype.add = function( sFilter ) {
  this.aData.push(sFilter);
};

SkipTable.prototype.delete = function ( sFilter ) {
  //TODO: implement this
};
