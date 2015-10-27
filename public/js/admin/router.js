// Filename: router.js

define([
    'jquery',
    'underscore',
    'backbone',
    ], function($, _, Backbone){
        var AppRouter = Backbone.Router.extend({
            routes: {
                '*actions': 'defaultAction'
            },
            
            defaultAction: function(actions){
            	function UrlExists(url)
            	{
            	    var http = new XMLHttpRequest();
            	    http.open('HEAD', url, false);
            	    http.send();
            	    return http.status!=404;
            	};
            	$url = $app.host + "/js/" + $app.module + "/views/" + $app.controller + ".js";

            	if(UrlExists($url)){
            		require([$url], function(view){
                        view.render();
                    });
            	}
            }
        });
        var initialize = function(){
            var app_router = new AppRouter;
//            app_router.on('route:'+$app.controller, function(){
//            	require(['views/'+$app.controller], function(view){
//            		view.render;
//            	});
//            });
            Backbone.history.start({
                pushState: true,
                root: "/"
            });
            var url = location.href;
        };
        return {
            initialize: initialize
        };
    });