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


	