/**
 * Author: Guntram Pollock & Sebastian Buckpesch
 */

/**
 * Loads a new template file into the div#main container using jquery animations
 * @param tmpl_filename Filename of the template
 */
function aa_tmpl_load(tmpl_filename, data) {
    show_loading(); // show the loading screen
    if ( typeof( data ) == 'undefined' ) {
    	data = '';
    } else {
    	data = '&' + data;
    }
    var url = "templates/" + tmpl_filename + "?aa_inst_id=" + aa_inst_id + data;
    $("#main").slideUp(0, function () {
        $("#main").load( url, function () {
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
            classes = 'alert alert-info fade in';
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

/**
 * Creates a var dump out of a javascript object.
 * @param {Object} obj The object to dump
 * @param {String} selector the selector to show the dumping in.
 * @param {boolean} overwrite set to true if the object shall overwrite the selectors content, or false to append stuff.
 */
function var_dump(obj, selector, overwrite) {
	if ( typeof( overwrite ) == 'undefined' || overwrite != true ) {
		overwrite = false;
	}
    var out = '';
    for (var i in obj) {
        out += i + ": " + obj[i] + "\n";
    }

    //console.log(out);
    
    if ( overwrite ) {
    	$( selector ).html( out );
    } else {
    	$( selector ).append( out );
    }
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

function open_popup( url, name ) {
    popup = window.open( url, name, 'target=_blank,width=820,height=800' );
    if ( window.focus ) {
        popup.focus();
    }
    return false;
}

function urlencode(str){str=(str+'').toString();return encodeURIComponent(str).replace(/!/g,'%21').replace(/'/g,'%27').replace(/\(/g,'%28').replace(/\)/g,'%29').replace(/\*/g,'%2A').replace(/%20/g,'+');}