<?php 
// Initialize Application
require_once('init.php');
?>
<!doctype html>
<!-- paulirish.com/2008/conditional-stylesheets-vs-css-hacks-answer-neither/ -->
<!--[if lt IE 7]> <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang="en"> <![endif]-->
<!--[if IE 7]>    <html class="no-js lt-ie9 lt-ie8" lang="en"> <![endif]-->
<!--[if IE 8]>    <html class="no-js lt-ie9" lang="en"> <![endif]-->
<!-- Consider adding a manifest.appcache: h5bp.com/d/Offline -->
<!--[if gt IE 8]><!--> <html class="no-js" lang="en"> <!--<![endif]-->
<head>
  <meta charset="utf-8">

  <!-- Use the .htaccess and remove these lines to avoid edge case issues.
       More info: h5bp.com/b/378 -->
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

  <title></title>
  <meta name="description" content="">

  <!-- Mobile viewport optimized: h5bp.com/viewport -->
  <meta name="viewport" content="width=device-width">

  <!-- Place favicon.ico and apple-touch-icon.png in the root directory: mathiasbynens.be/notes/touch-icons -->

	<link rel="stylesheet" href="css/bootstrap.css">
	<style>
	body {
	  padding-top: 60px;
	  padding-bottom: 40px;
	}
	</style>
	<link rel="stylesheet" href="css/bootstrap-responsive.css">
	<link rel="stylesheet" href="css/style.css">

	<script src="js/libs/modernizr-2.5.2-respond-1.1.0.min.js"></script>
</head>
<body>
	<!-- Prompt IE 6 users to install Chrome Frame. Remove this if you support IE 6.
       chromium.org/developers/how-tos/chrome-frame-getting-started -->
	<!--[if lt IE 7]><p class=chromeframe>Your browser is <em>ancient!</em> <a href="http://browsehappy.com/">Upgrade to a different browser</a> or <a href="http://www.google.com/chromeframe/?redirect=true">install Google Chrome Frame</a> to experience this site.</p><![endif]-->
	
	<div id="header-container">
		<header class="wrapper clearfix">
			<div class="navbar navbar-fixed-top">
	      		<div class="navbar-inner">
			        <div class="container">
			          <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
			            <span class="icon-bar"></span>
			            <span class="icon-bar"></span>
			            <span class="icon-bar"></span>
			          </a>
			          <div class="nav-collapse collapse" style="height: 0px; ">
			          	<nav>
				            <ul class="nav">
				              <li class="active"><a href="#">Homepage</a></li>
				              <li><a href="#item2">Menu-item2</a></li> 
				              <li><a href="#item3">Menu-item3</a></li>
				            </ul>
			            </nav>
			          </div><!--/.nav-collapse -->
			        </div>
			    </div>
	    	</div>
	    	<a id="logo" class="brand" href="#"><img src="#" alt="Logo" /></a>
	    </header>
	</div>

	<div id="main-container" class="container">
		<div id="main" class="wrapper clearfix">
			<!-- Main hero unit for a primary marketing message or call to action -->
			<div class="hero-unit">
				<h1>Hello, world!</h1>
				<p>This is a template for a simple marketing or informational
					website. It includes a large callout called the hero unit and three
					supporting pieces of content. Use it as a starting point to create
					something more unique.</p>
				<p>
					<a class="btn btn-primary btn-large">Learn more &raquo;</a>
				</p>
			</div>

			<!-- Example row of columns -->
			<div class="row">
				<div class="span4">
					<h2>Heading</h2>
					<p>Donec id elit non mi porta gravida at eget metus. Fusce dapibus,
						tellus ac cursus commodo, tortor mauris condimentum nibh, ut
						fermentum massa justo sit amet risus. Etiam porta sem malesuada
						magna mollis euismod. Donec sed odio dui.</p>
					<p>
						<a class="btn" href="#">View details &raquo;</a>
					</p>
				</div>
				<div class="span4">
					<h2>Heading</h2>
					<p>Donec id elit non mi porta gravida at eget metus. Fusce dapibus,
						tellus ac cursus commodo, tortor mauris condimentum nibh, ut
						fermentum massa justo sit amet risus. Etiam porta sem malesuada
						magna mollis euismod. Donec sed odio dui.</p>
					<p>
						<a class="btn" href="#">View details &raquo;</a>
					</p>
				</div>
				<div class="span4">
					<h2>Heading</h2>
					<p>Donec sed odio dui. Cras justo odio, dapibus ac facilisis in,
						egestas eget quam. Vestibulum id ligula porta felis euismod
						semper. Fusce dapibus, tellus ac cursus commodo, tortor mauris
						condimentum nibh, ut fermentum massa justo sit amet risus.</p>
					<p>
						<a class="btn" href="#">View details &raquo;</a>
					</p>
				</div>
			</div>
		</div>
	</div>

	<footer>
		<?$aa->getConfig("config_value_id");?>
  	</footer>


  	<!-- JavaScript at the bottom for fast page loading -->

  	<!-- Grab Google CDN's jQuery, with a protocol relative URL; fall back to local if offline -->
  	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
  	<script>window.jQuery || document.write('<script src="js/libs/jquery-1.7.1.min.js"><\/script>')</script>

	<!-- scripts concatenated and minified via build script -->
  	<script src="js/plugins.js"></script>
  	<script src="js/script.js"></script>
  	<!-- end scripts -->

  	<!-- Asynchronous Google Analytics snippet. Change UA-XXXXX-X to be your site's ID.
       mathiasbynens.be/notes/async-analytics-snippet -->
	<script>
	    var _gaq=[['_setAccount','UA-XXXXX-X'],['_trackPageview']];
	    (function(d,t){var g=d.createElement(t),s=d.getElementsByTagName(t)[0];
	    g.src=('https:'==location.protocol?'//ssl':'//www')+'.google-analytics.com/ga.js';
	    s.parentNode.insertBefore(g,s)}(document,'script'));
  	</script>
  
  	<div id="fb-root"></div>
	<script type="text/javascript">
		jQuery(document).ready(function($) {
			initApp();
		});
		
		window.fbAsyncInit = function() {
		    FB.init({
		      appId      : '<?=$aa_app_id?>', // App ID
		      //channelUrl : '//WWW.YOUR_DOMAIN.COM/channel.html', // Channel File
		      status     : true, // check login status
		      cookie     : true, // enable cookies to allow the server to access the session
		      xfbml      : true  // parse XFBML
		    });
		    // Additional initialization code here
		  };
	
		  // Load the SDK Asynchronously
		  (function(d){
		     var js, id = 'facebook-jssdk'; if (d.getElementById(id)) {return;}
		     js = d.createElement('script'); js.id = id; js.async = true;
		     js.src = "//connect.facebook.net/en_US/all.js";
		     d.getElementsByTagName('head')[0].appendChild(js);
		   }(document));
	</script>
  
</body>
</html>