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

	// USE values from INPUT fields here too!
	var obj = {
        method:'feed',
        link: $( '#link' ).val(),
        picture: $( '#picture' ).val(),
        name: $( '#name' ).val(),
        caption: $( '#caption' ).val(),
        description: $( '#message' ).val()
    };
	
/*
    // calling the API ...
    var obj = {
        method:'feed',
        link:link,
        picture:picture_url,
        name:name,
        caption:caption,
        description:desc
    };
*/
    

    FB.ui(obj, callback);
}
function sendToFriend(link, name) {
    FB.ui({
        method:'send',
        name: $( '#name' ).val(),
        link: $( '#link' ).val()
    }, callback);
}
function sendRequest(name, desc, data) {
    // Use FB.ui to send the Request(s)
    FB.ui({method:'apprequests',
        title: $( '#name' ).val(),
        message: $( '#message' ).val(),
        data:data
    }, callback);
}

function shareViaUrl() {
	var message      = $( '#message' ).val();
	var redirect_url = $( '#url' ).val();
	var link         = $( '#link' ).val();
	var picture      = $( '#picture' ).val();
	var caption      = $( '#caption' ).val();
	var name         = $( '#name' ).val();
	var url = 'https://www.facebook.com/dialog/feed' +
			  '?app_id=' + fb_app_id +
			  '&link=' + link +
			  '&picture=' + picture +
			  '&name=' + name +
			  '&caption=' + caption +
			  '&description=' + message +
			  '&redirect_uri=' + redirect_url;
	openPopup( url, 'share via url' );
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
 *
 * @param message Main message of the send dialog
 * @param redirect_url Url behind the title of the post (User will be redirected to this url, when he clicks on the title)
 * @param link
 * @param title Title of the send dialog popup
 */
function sendViaUrl( message, redirect_url, link, title ) {
    if ( typeof( title ) == 'undefined' ) {
        title = "Share";
    }
	var url = 'https://www.facebook.com/dialog/send' +
			  '?app_id=' + fb_app_id +
			  '&message=' + message +
			  '&link=' + link +
			  '&redirect_uri=' + redirect_url;
	openPopup( url, title );
}

function friendRequestViaUrl() {
	var message = $( '#message' ).val();
	var redirect_url = $( '#url' ).val();
	var url = 'https://www.facebook.com/dialog/apprequests' +
			  '?app_id=' + fb_app_id +
			  '&message=' + message + 
			  '&redirect_uri=' + redirect_url;
	openPopup( url, 'apprequest via url' );
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