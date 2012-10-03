/** 
 * Author: Guntram Pollock & Sebastian Buckpesch
 */

/**
 * Loads a new template file into the div#main container using jquery animations
 * @param tmpl_filename Filename of the template
 */
function aa_tmpl_load(tmpl_filename){
	show_loading(); // show the loading screen
	$("#main").slideUp( 0, function(){
		$("#main").load( "templates/" + tmpl_filename + "?aa_inst_id=" + aa_inst_id, function(){
			$("#main").slideDown(600,function(){
				//reinit facebook
				FB.init({
				   appId      : fb_app_id, // App ID
				   channelUrl : fb_canvas_url + 'channel.html', // Channel File
				   status     : true, // check login status
				   cookie     : true, // enable cookies to allow the server to access the session
				   xfbml      : true, // parse XFBML
				   oauth    : true
				});
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
function show_msg( msg, type, delay ) {
	
	if ( typeof( type ) !== 'string' ) {
		type = 'error';
	}
	
	if ( typeof( msg ) !== 'string' ) {
		msg = __e( 'something_went_wrong' );
	}
	
	var classes = 'alert fade in';
	
	switch( type ) {
	
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
	
	$( '#msg-container' ).slideUp( 500, function() {
		$( '#msg-container' ).alert();
		$( '#msg-container' ).removeClass().addClass( classes ).html( msg ).slideDown( 500 ).delay(delay).fadeOut('slow');
	});
	
}

	