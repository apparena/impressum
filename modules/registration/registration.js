/**
 * This module can be used to register users and save them to the database.
 * Include this module to use it:
 * <script src="modules/registration/registration.js" type="text/javascript" />
 * Use the functions like this:
 * <form method="post" action="javascript:$.registerUser();">...
 * or:
 * <button id="fb_connect" onclick="$.FBConnect('email,publish_stream');">...
 * @author Guntram Pollock 11/2012
 */

/**
 * This is a bootstrap validation var.
 * It defines the required fields and error handling.
 * The rules-keys are ids/names of form elements like inputs.
 * The <select> elements need to use class validation, so they
 * have to be added additionally (the question selects have dynamically generated ids/names).
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
            min: 3
        }
    }
};

$.registerUser = function( id, callback ) {
	
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
	
	$.userData = {}; // create a global user object to save it later
	
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
				
				if ( $(this).val().length > 0 ) {
					value = $.trim( $(this).val() );
				}
				
				break;
				
		} // end switch through this input elements type attribute value
		
		$.userData[ key ] = value; // add key and value to the user object
		
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
		
		$.userData[ key ] = {};
		
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
		
		$.userData[ key ] = value;
		
	}); // end loop through select elements
	
	$( '#progress-form' ).hide();
	
	$( '#saveUserData' ).remove();
	
	$( '#progress-form' ).after(
		'<button class="btn btn-success" id="saveUserData" onclick="$.saveUserData();"><i class="icon-download-alt icon-white"></i> Save user data</button>'
	);
	
};

$.FBConnect = function( scope, callback ) {
	
	if ( typeof( scope ) == 'function' ) {
		callback = scope;
		if ( typeof( $( '#scope' ).val() ) == 'string' ) {
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
					
					$.userData = {};
					
					// the response length may vary due to the scope used for the FB.login() function! 
					for( var key in response ) { // get all values from the response and save them in the user object
						
						var item = response[ key ];
						
						if ( key == 'id' ) {
							$.userData[ 'fb_user_id' ] = item; // do not use the key 'id' for a fb-user-id
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
				
				enableForm();
				$( '#progress-connect' ).hide();
				
				$( '#saveUserData' ).remove();
				$( '#progress-connect' ).after(
					'<button class="btn btn-success" id="saveUserData" onclick="$.saveUserData();"><i class="icon-download-alt icon-white"></i> Save user data</button>'
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


$.saveUserData = function() {
	
	disableForm();
	$( '#progress-form' ).show();
	
	$.ajax({
		type : 'POST',
		async : true,
		url : 'modules/registration/save_user.php?aa_inst_id=' + aa_inst_id,
		data : ({
			user: $.userData
		}),
		success : function(data) {
			$( '#progress-form' ).hide();
			enableForm();
		}
	});
	
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