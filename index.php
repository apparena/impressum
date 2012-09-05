<?php 
 	include_once( "init.php" );
?>
<!-- HTML5 standard doctype -->
<!doctype html> 
<!--[if lt IE 7]> <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang="en"> <![endif]-->
<!--[if IE 7]>    <html class="no-js lt-ie9 lt-ie8" lang="en"> <![endif]-->
<!--[if IE 8]>    <html class="no-js lt-ie9" lang="en"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="en"> <!--<![endif]-->
<html>
	<head>
		<meta charset="utf-8">
	    
		<!-- Facebook Meta Data -->
	    <meta property="fb:app_id" content="<?php echo $aa['instance']['fb_app_id']?>" />
	    <meta property="og:title" content="" />
	    <meta property="og:type" content="website" />
	    <meta property="og:url" content="<?php echo $aa['instance']['fb_page_url']."?sk=app_".$aa['instance']['fb_app_id']?>" />
	    <meta property="og:image" content="" />
	    <meta property="og:site_name" content="" />
	    <meta property="og:description" content=""/>
	    
	    <!-- We have no old school title in a facebook app -->
		<title></title>
		<meta name="description" content="">
		<meta name="author" content="iConsultants UG - www.app-arena.com">
		
		<meta name="viewport" content="width=device-width">
		
		<!-- Include css config values here -->
		<style type="text/css">
			<?php 
				echo $aa['config']['css_bootstrap']['value'];
				echo $aa['config']['css']['value'];
			?>
		</style>
		
	</head>
	
	<body>
		<!-- Here starts the header -->
		<!-- Prompt IE 6 users to install Chrome Frame. Remove this if you support IE 6.
		     chromium.org/developers/how-tos/chrome-frame-getting-started -->
		<!--[if lt IE 7]><p class=chromeframe>Your browser is <em>ancient!</em> <a href="http://browsehappy.com/">Upgrade to a different browser</a> or <a href="http://www.google.com/chromeframe/?redirect=true">install Google Chrome Frame</a> to experience this site.</p><![endif]-->
		
		<?php 
			/*
			 * Here you can integrate your fangate.
			 * The App-Arena framework will get the FB-object
			 * and store some information, like if the user is
			 * a fan of this page or if he is the page admin.
			 * (-> see debug info button)
			 * Config values have to be fetched by adding the ['value'] index.
			 * $session->fb or $aa['instance'] values do not need the ['value'] index.
			 */
			if ( $aa['fb']['is_fb_user_fan'] == false && $aa['config']['nofan_image_activated']['value']) { ?>
				<div class="page_non_fans_layer"> 
					<div class="img_non_fans">
						<!--<img src="<?php echo $aa['config']['nofan_image']['value']?>" />-->
					</div>
					<div id="non_fan_background">&nbsp;</div>
				</div>
		<?php }?>
		
		<!-- 
			This is a standard twitter bootstrap menu block.
			The classes for the <a> tags will be used by Javascript initialization later,
			where all <a> elements in the menu will be parsed and the "template-xxx" classes
			will be interpreted as links to the "/templates"-folder and put on the <a> tags
			as links (this is done by initApp() ).
			These links do not reload the page, but load the according content into the main-div.
			So a class in this section with a "template-welcome" class will automatically be
			a menu button to load the "/templates/welcome.phtml" file content into the main-div,
			if the method initApp() is called when the DOM has finished loading (as here later on
			@jquerys document.ready function).
		 -->
	    <div class="navbar navbar-fixed-top">
			<div class="navbar-inner">
	        	<div class="container-fluid">
	            	<nav>
						<ul class="nav">
							<!-- 
								The __p() and __t() functions are used to show translated content
								from the App-Arena translation settings.
								__p(): print a translation - no need to echo, will always be shown.
								__t(): translate only - need to echo this if it shall be shown.
									-> use __e() function from js, which translates only.
									-> use variable values for formatted printing:
										translation var definition in aa:
											var name		translation english			  translation german				 ...
											"user_votes" -> "user %s has voted %s times", "nutzer %s hat %s mal abgestimmt", "...probably some other languages", ...
										usage in php/js:
											__p('user_votes', $username, $howoften);
											echo __t('user_votes', $username, $howoften);
											alert( __e('user_votes', $username, $howoften) );
								The passed parameter is automatically translated to the current
								locale setting, which may be switched by a language selector.
								The language selector should only be shown if it is activated in
								the App-Arena app-model / app-instance.
							 -->
							<li><a class="template-welcome"><?php __p("Homepage");?></a></li>
							<li><a class="template-english"><?php __p("English");?></a></li>
							<li><a class="template-germany"><?php __p("Germany");?></a></li>
							<li><a class="template-form_validation"><?php __p("Form Validation");?></a></li>
							<li><a class="template-fb_demo"><?php __p("Facebook DEMO");?></a></li>
							<li><a class="template-terms"><?php __p("Terms & Conditions");?></a></li>
						</ul>
					</nav>
				</div>
			</div>
	    </div>
		
		<!-- this is the div you can append info/alert/error messages to (will be showing between the menu and the content by default) -->
		<div id="msg-container"></div> 
		
		<div class="custom-header">
			<?php 
				echo $aa['config']['custom_header']['value'];
				?>
		</div>
		
		<div id="main" class="container">
				<!-- the main content is managed by initApp() -->
		</div> <!-- #main -->
		
		<div class="custom-footer">
			<?php 
				echo $aa['config']['custom_footer']['value'];
			?>
		</div>
		
		<footer>
			<div class="terms-and-conditions-container">
				<?php //TODO: check this and make it readable
					$terms_and_conditions_link = "<a class='template-terms'>" . __t("Terms & Conditions") . "</a>";
					__p("This promotion is not associated to Facebook and is not promoted, supported or organized by Facebook. Please check the %s for further details", $terms_and_conditions_link);
				?>
			</div>
			
			<div class="branding">
				<?php 
					// The app arena branding will be shown if the app instance is a basic one.
					if ( isset( $aa['config']['footer_activated']['value'] ) && $aa['config']['footer_activated']['value'] == '1' ) {
						echo $aa['config']['footer']['value'];
					}
				?>
			</div>
		</footer>
	
		<!-- 
			Debug area
			This will show the App-Arena app-instance config values
			which are inherited from the app-model and might have been
			changed for this instance.
		-->
		<?php 
			if ( isset( $global->config['admin_debug_mode']['value'] ) && $global->config['admin_debug_mode']['value'] == '1' ) {
		?>
			<span class="btn" onclick='jQuery("#_debug").toggle();'>Show debug info</span>
			<div id="_debug" style="display:none;">
				<h1>Debug information</h1>
				<?php Zend_Debug::dump($session->fb, "session->fb");?>
				<?php Zend_Debug::dump($session->app, "session->app");?>
				<?php Zend_Debug::dump($aa['instance'], "session->instance");?>
				<?php Zend_Debug::dump($session->translation, "session->translation");?>
				<?php Zend_Debug::dump($aa['config'], "session->config");?>
				<?php Zend_Debug::dump($_COOKIE, "_COOKIE");?>
				<?php Zend_Debug::dump(parse_signed_request($_REQUEST['signed_request']), "decoded fb signed request");?>
			</div>
		<?php } ?>
		
		<?php 
	 		// Include the file for the loading screen. Use it later from Javascript to hide_loading() or show_loading().
	 		require_once( dirname(__FILE__).'/templates/loading_screen.phtml' );
	 	?>
	 	
		<script src="//ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
		<script>window.jQuery || document.write('<script src="js/libs/jquery-1.7.1.min.js"><\/script>')</script>
		
		<!-- scripts concatenated and minified via ant build script-->
		<script src="js/libs/modernizr-2.5.2-respond-1.1.0.min.js"></script>
		<script src="js/bootstrap.min.js"></script>
		<script src="js/plugins.js"></script>
		<script src="js/script.js?v3"></script>
		<script src="js/jquery.validate.min.js?v3"></script>
		<!-- end scripts-->
		
		<!-- google analytics stuff -->
		<!--<script>
			var _gaq=[['_setAccount','UA-XXXXX-X'],['_trackPageview']]; // Change UA-XXXXX-X to be your site's ID
			(function(d,t){var g=d.createElement(t),s=d.getElementsByTagName(t)[0];g.async=1;
			g.src=('https:'==location.protocol?'//ssl':'//www')+'.google-analytics.com/ga.js';
			s.parentNode.insertBefore(g,s)}(document,'script'));
		</script>-->
		<!-- /google analytics stuff -->
		
		<!--[if lt IE 7 ]>
			<script src="//ajax.googleapis.com/ajax/libs/chrome-frame/1.0.2/CFInstall.min.js"></script>
			<script>window.attachEvent("onload",function(){CFInstall.check({mode:"overlay"})})</script>
		<![endif]-->
		
		<div id="fb-root"></div>
		<script type="text/javascript">
			/*
			 * Init AppManager vars for js.
			 * These variables, which are initialized here,
			 * will be available from ALL javascript functions!
			 * (just like a global variable)
			 * If you would be writing:
			 * "var fb_app_id = '...';"
			 * instead, this would initialize a LOCAL variable which is NOT
			 * available from other functions in other files!
			 */
			fb_app_id     = '<?php echo $aa['instance']["fb_app_id"]?>';
			fb_canvas_url = '<?php echo $aa['instance']["fb_canvas_url"]?>';
			aa_inst_id    = '<?php echo $aa['instance']["aa_inst_id"]?>';

			// jquerys document ready function gets fired when the DOM has been finished loading.
			$(document).ready(function() {
				userHasAuthorized = false;
				show_loading(); // uses the formerly included "loading_screen.phtml" files function

				// This actually "arms" the app so the menu will be working.
				// If this is not called, the menu items (<a> tags) have to be handled manually!
				initApp();
			});
			
/*
			window.fbAsyncInit = function() {
				FB.init({
			      appId      : fb_app_id, // App ID
				  channelUrl : fb_canvas_url + 'channel.html', // Channel File
			      status     : true, // check login status
			      cookie     : true, // enable cookies to allow the server to access the session
			      xfbml      : true, // parse XFBML
			      oauth		 : true
			    });

			    // Additional initialization code here
				FB.getLoginStatus(function(response) {
			    	  if (response.status === 'connected') {
			    	    // the user is logged in and connected to your
			    	    // app, and response.authResponse supplies
			    	    // the users ID, a valid access token, a signed
			    	    // request, and the time the access token 
			    	    // and signed request each expire
			    	    fb_user_id   = response.authResponse.userID;
						fb_user_name = response.authResponse.userName;
			    	    fb_status = "connected";
						
			    	    var fb_accessToken = response.authResponse.accessToken;
			    	    userHasAuthorized = true; // To auth one time and not always let the auth popup go crazy ;).
		
			    	    // get user name
			    	    FB.api('/me', function(response) {
							fb_user_name = response.name;
				     	});
			    	  } else if (response.status === 'not_authorized') {
			    	    // the user is logged in to Facebook, 
			    	    //but not connected to the app
						//alert("not connected");
							fb_status = "not_authorized";
			    	  } else {
			    	    // the user isn't even logged in to Facebook.
			    		  fb_status = "not_logged_in";
			    	  }
				});
			};
*/
			
			// Load the SDK Asynchronously
			(function(d){
				var js, id = 'facebook-jssdk', ref = d.getElementsByTagName('script')[0];
				if (d.getElementById(id)) {return;}
				js = d.createElement('script'); js.id = id; js.async = true;
				js.src = "//connect.facebook.net/de_DE/all.js";
				ref.parentNode.insertBefore(js, ref);
			}(document));
		</script>
		
		<!-- Show admin panel if user is admin -->
		<?php // Show admin panel, when page admin
		if (is_fb_user_admin()) {
			//include_once 'admin/admin_panel.php';?>		
		<?php } ?>
		
	</body>
</html>
