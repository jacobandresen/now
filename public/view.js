Ext.application({
    name: 'MyApp',
    autoCreateViewport: false,

    launch: function() {
        this.initRoutes();
    },

    initRoutes: function() {
        var me = this;

        Ext.Loader.injectScriptElement('https://raw.github.com/mtrpcic/pathjs/master/path.min.js', function() {
            Path.map("#/result").to(function() {
                console.log("Cases");
            });

            Path.map("#/result/:id").to(function() {
                var id = this.params["id"];
                console.log("result %o", id);
            });

            Path.root('#/result');
            Path.listen();
        }, null, this);
    }
});
