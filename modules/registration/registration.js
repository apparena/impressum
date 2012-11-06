/**
 * The registration module can handle FB-connect registration
 * and form-registration with data entered manually by the
 * user. It saves the user data to any connected database,
 * if columns or tables are missing, it creates them.
 * @author: Guntram Pollock
 */


/**
 * Login the user via FB and request authorization if any.
 * After successful FB-connection, the user's data are checked
 * and prepared to be saved.
 */
function connect() {
	
	
	
	
}

function auth( scope, callback, params ) {
	FB.login( function( response ) {
		if ( response.authResponse ) {
			FB.api( '/me', function( response ) {
				if ( typeof( response.id ) != 'undefined' ) {
					fb_user_id = response.id;
					fb_user_name = response.name;
					fb_user_email = response.email;
				}
				
				if ( typeof( callback ) == 'function' ) {
					callback( params );
				}
			});
		} else {
			aa_tmpl_load( 'no_auth.phtml' );
		}
    }, { scope: scope });
	
}

function registerUser() {
	
	
	
	
}