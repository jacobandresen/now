var casper = require('casper').create({
    viewportSize: {
        width: 1024,
        height: 768
    }
});

var WebGTCrawler = Object.create(casper);
WebGTCrawler.loadCase = function (rowIndex, numberOfRows, callback) {
    var rowIndex = rowIndex;
    this.evaluate(function (rowIndex) {
        WebGT.App.main.gridPanel.getSelectionModel().selectRow(rowIndex);
        WebGT.loadCase();
    }, {
        rowIndex: rowIndex
    });

    this.wait(5000, function () {
        var caseData = this.evaluate(function () {
            return Ext.encode(WebGT.App.casesDataStore.data.items[0].json);
        });
        console.log("loadCase [" + rowIndex + "/" + numberOfRows +"]");
        console.log(caseData);
        rowIndex++;
        if (rowIndex <= numberOfRows) {
            this.loadCase( rowIndex, numberOfRows, callback);
        } else {
           console.log("callback from loadCase! rowIndex:" + rowIndex);
           if (callback) {
               callback();
           }
        }
    });
}

WebGTCrawler.loadMenu = function (menuLinks, menuPos, numberOfMenus, callback) {
    console.log("loadMenu [" + menuPos + "/" + numberOfMenus +"]");
    var menuLink = menuLinks[menuPos];
    console.log("menuLink:" + menuLink);
    this.click("#"+menuLink);

    this.wait(20000,
        function () {
            this.captureSelector(menuLink + '.png', 'html');
            var numberOfRows = this.evaluate(function () {
                return WebGT.App.main.gridPanel.getView().getRows().length;
            });
            this.loadCase(0, numberOfRows,
                function () {
                    menuPos++;
                    if (menuPos < numberOfMenus -1) {
                        WebGTCrawler.loadMenu( menuLinks , menuPos, numberOfMenus, callback);
                    } else {
                        if (callback) {
                           callback();
                        }
                    }
                }
            );
       },
       function () {
            console.log("result grid timeout");
       },
       5000000000
   );
}

WebGTCrawler.start('http://abtest.vd.dk/webgt3/index.html', function () {
        this.waitUntilVisible('[name=companyName]',function() {
        this.evaluate(function () {
            Ext.query('[name=companyName]')[0].value='TEST';
            Ext.query('[name=userName]')[0].value='TEST';
            Ext.query('[name=password]')[0].value='TEST';
            Ext.query('button:nodeValue(Log ind)')[0].click();
        });
        this.captureSelector('login.png', 'html');
        var menuLinks = JSON.parse(this.evaluate( function () {
            var links = [];
            Ext.query('.level2 > a').each( function (link) {
               links.push(link.id);
            });
            return Ext.encode(links);
         }));

        this.waitWhileVisible('[name=companyName]',
           function () {
               this.waitWhileVisible('.ext-el-mask',
                   function () {
                       this.loadMenu( menuLinks, 1, menuLinks.length-1, function () {
                           WebGTCrawler.exit()
                       });
                   },
                   function (){
                       console.log("grid timeout");
                   },
                   3000000);
           },

           function () {
              console.log("timeout");
           },
           500000000000
        );

   });
});

WebGTCrawler.run();
