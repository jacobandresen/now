var casper = require('casper').create({
    viewportSize: {
        width: 1024,
        height: 768
    }
});

casper.start();

function indexPage( page, callback ) {
    casper.open("http://localhost/now/add.php", {
        method: 'post',
        data: {
            'title': page.title,
            'body' : page.body
        }
    }).then( function () {
        this.debugHTML();
        if (callback) {
            callback();
        }
    });
}

indexPage( {'title': 'jacob', 'body': 'tester'} );

casper.run();
