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

function register_user( id, callback ) {
	
	$( '#progress-form' ).show();
	
	var selector = $( 'body' ).find( '.form-registration' );
	
	if ( typeof( id ) == 'function' ) {
		callback = id;
		id = null;
	} else {
		if ( typeof( id ) == 'string' ) {
			selector = id;
		}
	}
	
	$.user_data = {}; // create a global user object to save it later
	
	// loop through all input elements of the form and 
	selector.find( 'input' ).each( function( index ) {
		
		var key = 'default_' + index; // a default key in case no id and no name is set to the input element
		var value = 'empty'; // a default value in case there is an empty field without validation
		
		// use the id as a key or the name if there is no id
		if ( $(this).attr( 'id' ).length > 0 ) {
			key = $(this).attr( 'id' ); // use the id as the key
		} else {
			if ( $(this).attr( 'name' ).length > 0 ) {
				key = $(this).attr( 'name' ); // if there is no id, use the name attribute
			}
		}
		
		var type = $(this).attr( 'type' ); // get the type attribute value of the input element
		switch( type ) {
			
			case 'checkbox':
				
				if ( $(this).is( ':checked' ) ) {
					value = true;
				} else {
					value = false;
				}
				
				break;
				
			case 'radio':
				//TODO: check for radio groups!
				break;
				
				// for input elements with type="text" or type="password" and so on
			default:
				
				if ( $(this).attr( 'value' ).length > 0 ) {
					if ( $(this).attr( 'value' ).indexOf( 'email' ) >= 0 ) {
						key = 'key'; // save the email as the key, identifiying the user
					}
					value = $.trim( $(this).attr( 'value' ) );
				}
				
				break;
				
		} // end switch through this input elements type attribute value
		
		$.user_data[ key ] = value; // add key and value to the user object
		
	}); // end loop through all form input elements
	
	// loop through all select elements
	selector.find( 'select' ).each( function( index ) {
		
		var key = 'default_' + index;
		var value = 'empty';
		var multiSelector = $(this);
		
		if ( $(this).attr( 'id' ).length > 0 ) {
			key = $(this).attr( 'id' ); // use the id as the key
		} else {
			if ( $(this).attr( 'name' ).length > 0 ) {
				key = $(this).attr( 'name' ); // if there is no id, use the name attribute
			}
		}
		
		$.user_data[ key ] = {};
		
		$(this).find( 'option' ).each( function( aIndex ) {
			
			// if this option has a certain class, handle it here...
			if ( $(this).hasClass( 'myclass' ) ) {
				
				/* do something */
				
			}
			
			if ( $(this).attr( 'multiple' ) == 'multiple' ) { // handle multiselects
				
				var mValue = [];
				
				for ( var mIndex = 0; mIndex < multiSelector.length; mIndex++ ) {
					
		            if ( multiSelector[ mIndex ].is( ':selected' ) ) {
		            	mValue[ mIndex ] = $.trim( multiSelector[ mIndex ].text() );
		            }
		            
		        }
				
				if ( multiSelector.length > 0 ) {
					value = mValue.join( ', ' );
				}
				
			} else { // handle single selects
				
				value = $.trim( $(this).text() );
				
			}
			
		}); // end loop through options
		
		$.user_data[ key ] = value;
		
	}); // end loop through select elements
	
	$( '#progress-form' ).hide();
	
	if ( $( '#container_log' ).length > 0 ) {
		$( '#container_log' ).fadeIn( 300 );
	}
	
	$( '#saveUserData' ).remove();
	
	$( '#progress-form' ).after(
		'<button class="btn btn-success" id="saveUserData" onclick="$.save_user_data();"><i class="icon-download-alt icon-white"></i> Save user data</button>'
	);
	
}