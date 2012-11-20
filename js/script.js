/**
 * Author: Guntram Pollock & Sebastian Buckpesch
 */

/**
 * Loads a new template file into the div#main container using jquery animations
 * @param tmpl_filename Filename of the template
 */
function aa_tmpl_load(tmpl_filename, data) {
    show_loading(); // show the loading screen
    $("#main").slideUp(0, function () {
        $("#main").load("templates/" + tmpl_filename + "?aa_inst_id=" + aa_inst_id + "&" + data, function () {
            $("#main").slideDown(600, function () {
                //reinit facebook
                if (typeof(FB) === "object" && FB._apiKey === null) {
                    FB.init({
                        appId:fb_app_id, // App ID
                        channelUrl:fb_canvas_url + 'channel.html', // Channel File
                        status:true, // check login status
                        cookie:true, // enable cookies to allow the server to access the session
                        xfbml:true, // parse XFBML
                        oauth:true
                    });
                }
                FB.Canvas.scrollTo(0, 0);
                hide_loading(); // hide the loading screen
            });
        });
    });
}

/**
 * Show a message in the top msg-container (which is hidden usually)
 * @param msg the message to display
 * @param type one of: error, success, info - determines the classing of the msg-div-element.
 * @param delay number of milisecons until the message box disappears
 */
function show_msg(msg, type, delay) {

    if (typeof( type ) !== 'string') {
        type = 'error';
    }

    if (typeof( msg ) !== 'string') {
        msg = __e('something_went_wrong');
    }

    var classes = 'alert fade in';

    switch (type) {

        case 'error':
            classes = 'alert alert-error fade in';
            break;

        case 'success':
            classes = 'alert alert-success fade in';
            break;

        case 'info':
            classes = 'alert alert-success fade in';
            break;

        default:
            classes = 'alert alert-block fade in';
            break;
    }

    $('#msg-container').slideUp(500, function () {
        $('#msg-container').alert();
        $('#msg-container').removeClass().addClass(classes).html(msg).slideDown(500).delay(delay).fadeOut('slow');
    });

}

function postToFeed(link, picture_url, name, caption, desc) {

    // calling the API ...
    var obj = {
        method:'feed',
        link:link,
        picture:picture_url,
        name:name,
        caption:caption,
        description:desc
    };

    function callback(response) {
        document.getElementById('msg').innerHTML = "Post ID: " + response['post_id'];
    }

    FB.ui(obj, callback);
}
function sendToFriend(link, name) {
    FB.ui({
        method:'send',
        name:name,
        link:link
    });
}
function sendRequest(name, desc, data) {
    // Use FB.ui to send the Request(s)
    FB.ui({method:'apprequests',
        title:name,
        message:desc,
        data:data
    }, callback);
}

function callback(response) {
    console.log(response);
}

function log_login(arguments) {
    console.log('on-login called');
    console.log(arguments);

}

function disableForm() {
    $( 'body' ).find( 'input' ).each( function(){
        $(this).attr( 'disabled', 'disabled' );
    });
    $( 'body' ).find( 'button' ).each( function(){
        $(this).attr( 'disabled', 'disabled' );
    });
    $( 'body' ).find( 'select' ).each( function(){
        $(this).attr( 'disabled', 'disabled' );
    });
}

function enableForm() {
    $( 'body' ).find( 'input' ).each( function(){
        $(this).removeAttr( 'disabled' );
    });
    $( 'body' ).find( 'button' ).each( function(){
        $(this).removeAttr( 'disabled' );
    });
    $( 'body' ).find( 'select' ).each( function(){
        $(this).removeAttr( 'disabled' );
    });
}