var casper = require('casper').create({
    viewportSize: {
        width: 1024,
        height: 768
    }
});

var seen = [];
function getLinks() {
    var links = Ext.query('a.docClass');
    return Array.prototype.map.call(links, function (e) {
        return e.getAttribute('href');
    });
};

var ext4 = Object.create(casper);
ext4.start("http://localhost/ext4/docs/index.html#!/api");

ext4.indexPage =  function ( seen , currentPosition ) {
   ext4.thenOpen("http://localhost/ext4/docs/index.html#!/api");
   var link = "a[href='" + seen[currentPosition] +"']";
   var url = "http://localhost/ext4/docs/index.html" + seen[currentPosition];
   ext4.thenClick(link);
   ext4.wait(5, function () {
       var text = ext4.evaluate( function () {
           return (Ext.query('.doc-contents')[0].innerText);
           });

       var title = ext4.getTitle();

       //console.log("title:" + title);
       ext4.open( "http://localhost/now/add.php", {
           method: 'post',
           data: {
               user: 'ext4',
               url: url,
               title: title,
               body: text
               }
           })
       ext4.then(function () {
           ext4.debugHTML();
       });
       currentPosition = currentPosition + 1;
       if (currentPosition < seen.length) {
           ext4.indexPage( seen, currentPosition);
       } else {
           ext4.exit();
       }
    });
};

ext4.then(function () {
    var i, seen = this.evaluate(getLinks);
    ext4.indexPage( seen, 0 );
});

ext4.run();
