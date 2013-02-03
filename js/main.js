/* Preparation of the require config object */
require.config({
    baseUrl:'js',
    urlArgs: "bust=" +  (new Date()).getTime(), // Be sure to comment this line before deploying app to live stage
    paths:{
        jquery:'//cdnjs.cloudflare.com/ajax/libs/jquery/1.8.3/jquery.min',
        bootstrap:'//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/2.2.2/bootstrap.min',
        facebook:'//connect.facebook.net/de_DE/all',
        script:'script'
    },
    shim:{ // load required non AMD modules here...
        jquery:{
            exports:'$'
        },
        bootstrap:{
            deps:[ 'jquery' ]
        },
        facebook:{
            exports:'FB'
        },
        script:{
            deps:[ 'jquery', 'facebook' ]
        }
    }
});

// the main.js uses REQUIRE instead of define to set up the scripts (aliases) our app needs to run
// (the aliases are mapped in the require.config() above).
require([
    'facebook',
    'bootstrap',
    'script'
], function (FB, bootstrap, script) {

    FB.init({
        appId:aa.inst.fb_app_id, // App ID
        channelUrl:aa.inst.fb_canvas_url + '/channel.php', // Channel File
        status:true, // check login status
        cookie:true, // enable cookies to allow the server to access the session
        xfbml:true, // parse XFBML
        oauth:true,
        frictionlessRequests:true
    });
    FB.Canvas.setAutoGrow();
    /* Hide Fangate, if user clicks the like button */
    FB.Event.subscribe('edge.create', function(response) {
        $('#fangate').hide();
    });

    aa_tmpl_load("index.phtml");
    $('#terms-link').click(function () {
        aa_tmpl_load('terms.phtml');
    });

});