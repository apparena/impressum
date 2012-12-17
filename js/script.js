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
	var obj = {
        method:'feed',
        link: $( '#link' ).val(),
        picture: $( '#picture' ).val(),
        name: $( '#name' ).val(),
        caption: $( '#caption' ).val(),
        description: $( '#message' ).val()
    };
    FB.ui(obj, callback);
}

/**
 *
 * @param name
 * @param link
 * @param display Can be: page, popup, iframe, or touch @see https://developers.facebook.com/docs/reference/dialogs/#display
 * @param callback
 */
function fb_send( name, message, link, picture, redirect_uri, to, display, callback ) {
    if ( typeof( display ) == 'undefined' ) {
        display = "page";
    }

    FB.ui({
        method:'send',
        name: name,
        description: message,
        link: link,
        picture: picture,
        redirect_uri: redirect_uri,
        to: to,
        display: display
    }, callback);
}

/**
 * Opens a popup with a send dialog. Easy to use...
 * @param message Main message of the send dialog
 * @param redirect_url Url behind the title of the post (User will be redirected to this url, when he clicks on the title)
 * @param link
 * @param title Title of the send dialog popup
 */
function fb_send_url( message, redirect_url, link, popup_title ) {
    if ( typeof( popup_title ) == 'undefined' ) {
        popup_title = "Share";
    }
    var url = 'https://www.facebook.com/dialog/send' +
        '?app_id=' + fb_app_id +
        '&message=' + message +
        '&link=' + link +
        '&redirect_uri=' + redirect_url;
    openPopup( url, popup_title );
}

/**
 * Opens the facebook multi friend selector dialog.
 * @param name Title of the request
 * @param message Message send to the user. The user will only see the message, if he authorized your facebook app before.
 * @param data Additional parameter, which can be passed with the request.
 * @param callback Callback function
 */
function sendRequest( name, message, data, callback ) {
    // Use FB.ui to send the Request(s)
    FB.ui({method:'apprequests',
        title: name,
        message: message,
        data:data
    }, callback);
}

/**
 * Opens a facebook sharing dialog popup
 * @param message Main message fof the sharing dialog
 * @param redirect_url
 * @param link
 * @param picture Url of pictures shared with the message
 * @param caption Subtitle for the sharing message
 * @param name Title of the sharing message
 * @param popup_title Sharing dialog popup title
 */
function shareViaUrl( message, redirect_url, link, picture, caption, name, popup_title) {
    if ( typeof( popup_title ) == 'undefined' ) {
        popup_title = "Share";
    }
	var url = 'https://www.facebook.com/dialog/feed' +
			  '?app_id=' + fb_app_id +
			  '&link=' + link +
			  '&picture=' + picture +
			  '&name=' + name +
			  '&caption=' + caption +
			  '&description=' + message +
			  '&redirect_uri=' + redirect_url;
	openPopup( url, popup_title );
}

function sharerViaUrl( message ) {
	var message      = $( '#message' ).val();
	var redirect_url = $( '#url' ).val();
	var link         = $( '#link' ).val();
	var picture      = $( '#picture' ).val();
	var caption      = $( '#caption' ).val();
	var name         = $( '#name' ).val();
	
/*
	var html='<button name="fb_share" class="btn btn-inverse btn-share" onclick="window.open(\''+url+'\',\'sharer\',\'toolbar=0,status=0,width='+params.width+',height='+params.height+'\');" href="javascript: void(0)">';
	    html+='<i class="icon-bullhorn icon-white"></i> ';
	    html+=__e('share');
	    html+='</button>';
*/
	
	var url='http://www.facebook.com/sharer.php?s=100&amp;p[title]=' + urlencode( name );
	    url += '&amp;p[summary]=' + urlencode( message );
	    url += '&amp;p[url]=' + urlencode( redirect_url );
	    url += '&amp;&amp;p[images][0]=' + urlencode( picture );
	
	
/*
	var url = 'https://www.facebook.com/sharer/sharer.php' +
			  '?app_id=' + fb_app_id +
			  '&link=' + link +
			  '&picture=' + picture +
			  '&name=' + name +
			  '&caption=' + caption +
			  '&description=' + message +
			  '&redirect_uri=' + redirect_url;
*/
	
	openPopup( url, 'share via url' );
}


/**
 * Opens a Multifriend-Selector Dialog
 * @param message Message send to the user. The user will only see the message, if he authorized your facebook app before.
 * @param redirect_url Url the request receiver will be redirected to, if he accepts the request
 * @param popup_title Title of the multi friend selector popup
 */
function friendRequestViaUrl( message, redirect_url, popup_title) {
    if ( typeof( popup_title ) == 'undefined' ) {
        popup_title = "Apprequest";
    }
	var url = 'https://www.facebook.com/dialog/apprequests' +
			  '?app_id=' + fb_app_id +
			  '&message=' + message + 
			  '&redirect_uri=' + redirect_url;
	openPopup( url, popup_title );
}

function callback(response) {
	var_dump( response, '#api_response', true );
    console.log(response);
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

function openPopup( url, name ) {
	
	popup = window.open( url, name, 'target=_blank,width=820,height=800' );
	if ( window.focus ) {
		popup.focus();
	}
	return false;
	
}

function urlencode(str){str=(str+'').toString();return encodeURIComponent(str).replace(/!/g,'%21').replace(/'/g,'%27').replace(/\(/g,'%28').replace(/\)/g,'%29').replace(/\*/g,'%2A').replace(/%20/g,'+');}