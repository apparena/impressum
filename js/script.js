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





/**************************************
 * this is only for debugging!        *
 * it will later be put into the      *
 * registration module folder...      *
 **************************************/
function FBConnect( id, callback ) {
	
	var scope = 'email';
	
	if ( typeof( id ) != 'undefined' && id != null && id.length > 0 ) {
		scope = $( '#' + id ).val();
	}
	
	authUser( scope, callback );
	
}



function registerUser( id, callback ) {
	
	$( '#progress-form' ).show();
	
	var selector = $( 'body' ).find( 'form' );
	
	if ( typeof( id ) == 'function' ) {
		callback = id;
		id = null;
	} else {
		if ( typeof( id ) == 'string' ) {
			selector = id;
		}
	}
	
	$userData = {}; // create a global user object to save it later
	
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
				
				if ( this.val().length > 0 ) {
					value = $.trim( $(this).val() );
				}
				
				break;
				
		} // end switch through this input elements type attribute value
		
		$userData[ key ] = value; // add key and value to the user object
		
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
		
		$userData[ key ] = {};
		
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
		
		$userData[ key ] = value;
		
	}); // end loop through select elements
	
	$( '#progress-form' ).hide();
	
	$( '#saveUserData' ).remove();
	
	$( '#progress-form' ).insertAfter(
		'<button class="btn btn-success" id="saveUserData" onclick="saveUser();"><i class="icon-download-alt icon-white"></i> </button>'
	);
	
}

function authUser( scope, callback ) {
	
	FB.login( function( response ) {
		
		if ( response.authResponse ) {
			
			FB.api( '/me', function( response ) {
				
				if ( typeof( response.id ) != 'undefined' ) {
					
					// the response length may vary due to the scope used for the FB.login() function! 
					for( key in response ) { // get all values from the response and save them in the user object
						
						var item = response[ key ];
						
						if ( key == 'id' ) {
							$.userData[ 'fb_user_id' ] = response.id; // do not use the key 'id' for a fb-user-id
						} else {
							$.userData[ key ] = item;
						}
						
					}
					
/*
					// if you only use 'email' as a scope for FB.login()
					$.userData[ 'fb_user_id' ] = response.id;
					$.userData[ 'name' ]       = response.name;
					$.userData[ 'email' ]      = response.email;
*/
					
				}
				
				if ( typeof( callback ) == 'function' ) {
					callback();
				}
			});
			
		} else {
			// the user did not accept the FB.login() authorization request!
			aa_tmpl_load( 'no_auth.phtml' );
		}
		
    }, { scope: scope });
	
}


function saveUserData() {
	
	disableForm();
	$( '#progress-form' ).show();
	
	$.ajax({
		type : 'POST',
		async : true,
		url : 'save_user.php?aa_inst_id=' + aa_inst_id,
		data : ({
			user: $.userData
		}),
		success : function(data) {
			
		}
	});
	
	$( '#progress-form' ).hide();
	enableForm();
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