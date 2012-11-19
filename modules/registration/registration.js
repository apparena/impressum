/**
 * This module can be used to register users and save them to the database.
 * Include this module to use it:
 * <script src="modules/registration/registration.js" type="text/javascript" />
 * Use the functions like this:
 * <form method="post" action="javascript:$.register_user();">...
 * or:
 * <button id="fb_connect" onclick="$.fb_connect('email,publish_stream');">...
 * @author Guntram Pollock 11/2012
 */

/**
 * This is a bootstrap validation var.
 * It defines the required fields and error handling.
 * The rules-keys are ids/names of form elements like inputs.
 * The <select> elements need to use class validation, so they
 * have to be added additionally (the selects have dynamically generated ids/names).
 * Therefore the jquery validation has to be extended by:
 * $.validator.addClassRules({class_name:{required: true}});
 * This must be done before calling validate(...).
 */
$.bootstrap_form = {
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

$.register_user = function ( id, callback ) {
	
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
					if ( key.indexOf( 'email' ) >= 0 ) {
						key = 'key'; // save the email as the key, identifiying the user. use a field containing the word email in the id or name.
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
	
};

$.fb_connect = function ( scope, callback ) {
	
	if ( typeof( scope ) == 'function' ) {
		callback = scope;
		if ( typeof( $( '#scope' ).val() ) == 'string' && $( '#scope' ).val().length > 0 ) {
			scope = $( '#scope' ).val();
		} else {
			scope = 'email';
		}
	}
	
	disableForm();
	$( '#progress-connect' ).show();
	
	FB.login( function( response ) {
		
		if ( response.authResponse ) {
			
			FB.api( '/me', function( response ) {
				
				if ( typeof( response.id ) != 'undefined' ) {
					
					$.user_data = {};
					
					// the response length may vary due to the scope used for the FB.login() function! 
					for( var key in response ) { // get all values from the response and save them in the user object
						
						var item = response[ key ];
						
						if ( key == 'id' ) {
							$.user_data[ 'key' ] = item; // use the key 'key' for a fb-user-id as an user-identificator
						} else {
							$.user_data[ key ] = item;
						}
						
					}
					
					if ( $( '#user_profile' ).length > 0 ) {
						$( '#user_profile' ).html(
							$.user_data[ 'name' ] + '&nbsp;&nbsp;&nbsp;'
							+ '<a href="https://www.facebook.com/' + $.user_data[ 'key' ] + '" target="_blank">'
							+'<img class="profile-picture" src="https://graph.facebook.com/'
							+ $.user_data[ 'key' ]
							+ '/picture?type=square" title="visit the users facebook profile..." /></a>'
						);
					}
					
					if ( $( '#container_log' ).length > 0 ) {
						$( '#container_log' ).fadeIn( 300 );
					}
					
/*
					// if you only use 'email' as a scope for FB.login()
					$.user_data[ 'key' ]   = response.id;
					$.user_data[ 'name' ]  = response.name;
					$.user_data[ 'email' ] = response.email;
*/
					
				}
				
				enableForm();
				$( '#progress-connect' ).hide();
				
				$( '#saveUserData' ).remove();
				$( '#progress-connect' ).after(
					'<button class="btn btn-success" id="saveUserData" onclick="$.save_user_data();"><i class="icon-download-alt icon-white"></i> Save user data</button>'
				);
				
				if ( typeof( callback ) == 'function' ) {
					callback();
				}
				
			});
			
		} else {
			// the user did not accept the FB.login() authorization request!
			enableForm();
			$( '#progress-connect' ).hide();
			
			aa_tmpl_load( 'no_auth.phtml' );
		}
		
    }, { scope: scope });
	
};


$.save_user_data = function() {
	
	disableForm();
	$( '#progress-form' ).show();
	
	$.ajax({
		type : 'POST',
		async : true,
		url : 'modules/registration/save_user.php?aa_inst_id=' + aa_inst_id,
		data : ({
			user: $.user_data
		}),
		success : function(data) {
			
			$( '#progress-form' ).hide();
			enableForm();
			
		}
	});
	
};

/**
 * Log an action for an existing user.
 * @param string action the action type to log, e.g. 'register' or 'invite'.
 * @param string data the additional data to save for this log item, e.g. FB user ids of invited friends.
 */
$.log_action = function ( action, data ) {
	
	disableForm();
	$( '#progress-log' ).show();
	
	$.user_log = {};
	
	$.user_log[ 'action' ] = 'register';
	$.user_log[ 'data' ]   = 'sample data';
	
	if ( $( '#action' ).length > 0 ) {
		if ( $( '#action' ).val().length > 0 ) {
			$.user_log[ 'action' ] = $( '#action' ).val(); 
		}
	}
	
	if ( typeof( action ) != 'undefined' && action != null && action.length > 0 ) {
		$.user_log[ 'action' ] = action;
	}
	
	if ( typeof( data ) != 'undefined' && data != null && data.length > 0 ) {
		$.user_log[ 'data' ] = data;
	}
	
	$.user_log[ 'key' ] = $.user_data[ 'key' ];
	
	$.ajax({
		type : 'POST',
		async : true,
		url : 'modules/registration/log_user_action.php?aa_inst_id=' + aa_inst_id,
		data : ({
			log: $.user_log
		}),
		success : function(data) {
			$( '#progress-log' ).hide();
			enableForm();
		}
	});
	
};

/**
 * Shows the fb-register form in the desired id.
 * If the params are empty, it will try to display the form appended to the body.
 * @param fields The fields to query from the user. These fields will be available later to save them in the db.
 * @param url The url will be called by Facebook including a signed request when the user confirms the form.
 * @param put_to_id Specify a HTML-id to put the registration form into after creating it.
 */
$.fb_register = function ( fields, url, put_to_id ) {

    if ( typeof( fields ) == 'undefined' || fields.length <= 0 ) {
        if ( $( '#fields' ).length > 0 ) {
            fields = $( '#fields').val();
        } else {
            fields = 'name'; // at least one default field
        }
    }

    if ( typeof( url ) == 'undefined' || url.length <= 0 ) {
        if ( $( '#url' ).length > 0 ) {
            url = $( '#url' ).val();
        } else {
            // default url
            url = 'https://www.app-arena.com/app/aa_template/dev/modules/registration/save_user.php?aa_inst_id=' + aa_inst_id;
        }
    }

    if ( typeof( put_to_id ) == "undefined" || $( '#' + put_to_id ).length <= 0 ) {
        put_to_id = 'fb_registration';
    }

    if ( $( '#' + put_to_id ).length <= 0 ) {
        $( 'body').append( '<div id="fb_registration"></div>' );
    }

    var fb_registration = '<fb:registration '
        + 'fields="' + fields + '" '
        + 'redirect-uri="' + url + '" '
        + 'width="530" ' +
        + 'on-login="log_login(arguments);">'
        + '</fb:registration>';
    $( '#' + put_to_id ).html( fb_registration );

    FB.XFBML.parse();

};


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