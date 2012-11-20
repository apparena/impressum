/**
 * This module can be used to register users and save them to the database.
 * Include this module to use it:
 * <script src="modules/registration/registration.js" type="text/javascript" />
 * Use the functions like this:
 * <form method="post" action="javascript:$.register_form();">...
 * or:
 * <button id="fb_connect" onclick="$.register_fb_connect('email,publish_stream');">...
 * The user data will be stored in a global object $.register_user_data.
 * @author Guntram Pollock 11/2012
 */

/**
 * This is a bootstrap validation var.
 * It defines the required fields and error handling.
 * The rules-keys are ids/names of form elements like inputs.
 * <select> elements might need to use class validation, so they
 * have to be added additionally (selects with dynamically generated ids/names).
 * Therefore the jquery validation has to be extended by:
 * $.validator.addClassRules({class_name:{required: true}});
 * This must be done before calling validate(...).
 */
$.register_bootstrap_form = {
    errorClass: "error",
    validClass: "success",
    errorElement: "span",
    highlight: function(element, errorClass, validClass) {
        if (element.type === 'radio') {
            this.findByName(element.name ).closest(".control-group").removeClass(validClass).addClass(errorClass);
        } else {
            $(element).closest(".control-group").removeClass(validClass).addClass(errorClass);
        }
    },
    unhighlight: function(element, errorClass, validClass) {
        if (element.type === 'radio') {
            this.findByName(element.name ).closest(".control-group").removeClass(errorClass).addClass(validClass);
        } else {
            $(element).closest(".control-group").removeClass(errorClass).addClass(validClass);
        }
    },
    errorPlacement: function(error, element) {
        error.prependTo(element.closest(".control-group"));
    },
    rules:{
        email:{
            required:true,
            email:true
        },
        name:{
            required:true,
            minlength: 3
        }
    }
};

/**
 * Read the inputs from the user and save them in a global user variable ($.register_user_data).
 * @param {String} id The selector which will be looped to find fields, which it must contain.
 * @param {Function} callback A callback function to call when the user data has been stored in the $.register_user_data object.
 */
$.register_form = function ( id, callback ) {
	
	var selector = $( 'body' ).find( '.form-registration' ); // default selector if no id-parameter is set
	
	if ( typeof( id ) == 'function' ) {
		callback = id;
		id = null;
	} else {
		if ( typeof( id ) == 'string' ) {
			selector = $( 'body' ).find( id );
		}
	}
	
	$.register_user_data = {}; // create a global user object to save it later to the db
	
	// loop through all input elements of the form
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
					if ( key.indexOf( 'email' ) >= 0 ) {
						key = 'key'; // save the email as the key, identifying the user. use a field containing the word email in the id or name.
					}
					value = $.trim( $(this).attr( 'value' ) );
				}
				
				break;
				
		} // end switch through this input elements type attribute value
		
		$.register_user_data[ key ] = value; // add key and value to the user object
		
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
		
		$.register_user_data[ key ] = {};

        // loop through the options of this select element
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
				
				if ( multiSelector.length > 1 ) {
					value = mValue.join( ', ' );
				}
				
			} else { // handle single selects
				
				value = $.trim( $(this).text() );
				
			}
			
		}); // end loop through options
		
		$.register_user_data[ key ] = value;
		
	}); // end loop through select elements

    if ( typeof callback == 'function' ) {
        callback();
    }
	
}; // end register_form()


/**
 * Connects the user via Facebook.
 * Will use 'email' as a scope if no scope is set.
 * @param {String} scope The FB.api-scope (permission(s)) can be comma separated like "email, publish_stream".
 * @param {Function} callbackSuccess This callback gets executed when the user accepts the FB dialog.
 * @param {Function} callbackError This callback gets executed when the user cancels the FB dialog.
 */
$.register_fb_connect = function ( scope, callbackSuccess, callbackError ) {
	
	if ( typeof( scope ) == 'function' ) {
        if ( typeof callbackSuccess == 'function' ) {
            callbackError = callbackSuccess;
        }
		callbackSuccess = scope;
        scope = null;
	}
    if ( typeof scope != 'string' ) {
        scope = 'email'; // default scope
    }
	
	FB.login( function( response ) {
		
		if ( response.authResponse ) {
			
			FB.api( '/me', function( response ) {
				
				if ( typeof( response.id ) != 'undefined' ) {
					
					$.register_user_data = {};
					
					// the response length may vary due to the scope used for the FB.login() function! 
					for( var key in response ) { // get all values from the response and save them in the user object
						
						var item = response[ key ];
						
						if ( key == 'id' ) {
							$.register_user_data[ 'key' ] = item; // use the key 'key' for a fb-user-id as an user-identificator
						} else {
							$.register_user_data[ key ] = item;
						}
						
					}
					
/*
					// if you only use 'email' as a scope for FB.login()
					$.register_user_data[ 'key' ]   = response.id;
					$.register_user_data[ 'name' ]  = response.name;
					$.register_user_data[ 'email' ] = response.email;
*/
					
				}
				
				if ( typeof( callbackSuccess ) == 'function' ) {
                    callbackSuccess();
				}
				
			}); // end FB.api call
			
		} else {
			// the user did not accept the FB.login() authorization request!
            if ( typeof( callbackError ) == 'function' ) {
                callbackError();
            }
		}
		
    }, { scope: scope }); // end FB.login call
	
}; // end register_fb_connect()


/**
 * Shows the fb-register form widget in the desired id.
 * If the params are empty, it will try to display the form appended to the body.
 * Note that the fb widget might show up an error message, e.g. if 'name' is not the first field or the fields are not set up correctly.
 * You can specify own input elements by passing a JSON object rather than a comma-separated string.
 * Note that if you do not pass a valid string or json with the fields, the default field 'name' will be used.
 * Note that the put_to parameter will be defaulted to 'body' if it is missing, so that the widget will be appended to the body by default.
 * @param {String|Object} fields The fields to query from the user. These fields will be available later to save them in the db.
 * @param {String} url The url will be called by Facebook including a signed request when the user confirms the form.
 * @param {String} put_to Specify an HTML-element by a selector to put the registration form into after creating it, e.g. '#myId', '.myClass'.
 * @return {Object} This function will return an error object if the put_to element selector is missing, or a success object if there was no error.
 */
$.register_fb_widget = function ( fields, url, put_to ) {

    if ( typeof( fields ) == 'undefined' || fields.length <= 0 ) {
        fields = 'name'; // at least one default field
    }

    if ( typeof( url ) == 'undefined' || url.length <= 0 ) {
        // default url
        url = 'https://www.app-arena.com/app/aa_template/dev/modules/registration/save_user.php?aa_inst_id=' + aa_inst_id;
    }

    if ( typeof( put_to ) == "undefined" || $( '' + put_to ).length <= 0 ) {
        put_to = 'body';
    }

    if ( $( '' + put_to ).length <= 0 ) {
        return {"error":"you must provide an element where the generated fb-widget will be placed"};
    }

    if ( $( '#register_fb_widget').length <= 0 ) {
        $( '#register_fb_widget' ).remove(); // remove the div if it is already there to prevent duplicate widgets showing up
    }

    var fb_registration = '<div id="register_fb_widget"><fb:registration '
        + 'fields="' + fields + '" '
        + 'redirect-uri="' + url + '" '
        + 'width="530" '
        + ' ></fb:registration></div>';

    $( '' + put_to ).append( fb_registration );

    FB.XFBML.parse(); // render the xfbml tag <fb:registration>

    return {"success":true};

}; // end register_fb_widget()

/**
 * Save the user data objects content to the db.
 * Calls the save_user.php file which will do the save process.
 * @param {Function} callbackSuccess <p>This function will be called when the save_user.php has finished saving the user / determining if the user existed.
 *                                   The received response will be passed to the callback function as a parameter.</p>
 * @param {Function} callbackError This function will be called when the save_user.php file is not found or the server is down.
 */
$.register_save_user_data = function( callbackSuccess, callbackError ) {
	
	$.ajax({
		type : 'POST',
		async : true,
		url : 'modules/registration/save_user.php?aa_inst_id=' + aa_inst_id,
		data : ({
			user: $.register_user_data
		}),
		success : function( data ) {
            if ( typeof callbackSuccess == 'function' ) {
                callbackSuccess( data );
            }
		},
        error: function( jqXHR, textStatus, errorThrown ) {
            if ( typeof callbackError == 'function' ) {
                var errorObject = {};
                errorObject[ 'jqXHR' ]       = jqXHR;
                errorObject[ 'textStatus' ]  = textStatus;
                errorObject[ 'errorThrown' ] = errorThrown;
                callbackError( errorObject );
            }
        }
	}); // end save_user.php ajax call
	
}; // end register_save_user_data()

/**
 * Log an action for an existing user.
 * @param {String} action the action type to log, e.g. 'register' or 'invite'.
 * @param {String} data the additional data to save for this log item, e.g. FB user ids of invited friends.
 * @param {Function} callbackSuccess <p>This function will be called when the log_user_action.php has finished saving the log.
 *                                   The received response will be passed to the callback function as a parameter.</p>
 * @param {Function} callbackError This function will be called when the log_user_action.php file is not found or the server is down.
 */
$.register_log_action = function ( action, data, callbackSuccess, callbackError ) {
	
	$.user_log = {};
	
	$.user_log[ 'action' ] = 'register';
	$.user_log[ 'data' ]   = '';
	
	if ( typeof( action ) != 'undefined' && action != null && action.length > 0 ) {
		$.user_log[ 'action' ] = action;
	}

	if ( typeof( data ) != 'undefined' && data != null && data.length > 0 ) {
		$.user_log[ 'data' ] = data;
	}
	
	$.user_log[ 'key' ] = $.register_user_data[ 'key' ];
	
	$.ajax({
		type : 'POST',
		async : true,
		url : 'modules/registration/log_user_action.php?aa_inst_id=' + aa_inst_id,
		data : ({
			log: $.user_log
		}),
		success : function( data ) {
            if ( typeof callbackSuccess == 'function' ) {
                callbackSuccess( data );
            }
		},
        error: function( jqXHR, textStatus, errorThrown ) {
            if ( typeof callbackError == 'function' ) {
                var errorObject = {};
                errorObject[ 'jqXHR' ]       = jqXHR;
                errorObject[ 'textStatus' ]  = textStatus;
                errorObject[ 'errorThrown' ] = errorThrown;
                callbackError( errorObject );
            }
        }
	}); // end log_user_action.php ajax call
	
}; // end register_log_action()