function SkipTable (sUrl,sName,idDiv) {
  this.sUrl=sUrl; 
  this.idDiv=idDiv; 
  this.sName=sName; 
  this.aData=Array(); 
};

SkipTable.prototype.render = function () {
  var iPos=0;
  if(this.aData) {
    var sContent="<table>"; 
    var elm=document.getElementById(this.idDiv);
    for(iPos=0;iPos<this.aData.length;iPos++){
       sContent+="<tr>"; 
sContent+="<td><input type=\"text\" value=\""+this.aData[iPos]+"\"/></td>";
sContent+="<td><input type=\"button\" onClick=\""+this.sName+".delete("+iPos+")\" class=\"delete\" value=\"delete\"/></td>" ;
sContent+="</tr>"; 
    }
    sContent+="</table>"; 
    elm.innerHTML=sContent;
  }
};

SkipTable.prototype.update = function () {
  var request=
   new Request.JSON({url: this.sUrl,
                onComplete: function(list) {
                  this.aData=list;
                  this.render();  
                }.bind(this)
              });
  request.post({"name":this.sName});
};

SkipTable.prototype.add = function( sFilter ) {
  this.aData.push(sFilter);
};

SkipTable.prototype.delete = function ( iPos ) {
  var request= 
    new Request.JSON({url: this.sUrl,
                onComplete: function(list){
                    this.aData=list;
                    this.render();
                 }.bind(this)
                });
     request.post({"name":this.sName},{"action":delete}); 
                

};
