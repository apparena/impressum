<?php 
 	include_once( "init.php" );
?>
<!doctype html>
<!--[if lt IE 7]> <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang="en"> <![endif]-->
<!--[if IE 7]>    <html class="no-js lt-ie9 lt-ie8" lang="en"> <![endif]-->
<!--[if IE 8]>    <html class="no-js lt-ie9" lang="en"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="en"> <!--<![endif]-->
<html>
	<head>
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
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
			if ( $aa['fb']['is_fb_user_fan'] == false && $aa['config']['nofan_image_activated']['value']) { ?>
				<div class="page_non_fans_layer">
					<div class="img_non_fans">
						<img src="<?php echo $aa['config']['nofan_image']['value']?>" />
					</div>
					<div id="non_fan_background">&nbsp;</div>
				</div>
		<?php }?>
		
		
	    <div class="navbar navbar-fixed-top">
			<div class="navbar-inner">
	        	<div class="container-fluid">
	            	<nav>
						<ul class="nav">
							<li><a onclick="aa_tmpl_load('index.phtml');"><?php __p("Home");?></a></li>
							<li><a onclick="aa_tmpl_load('localization.phtml');"><?php __p("localization");?></a></li>
                            <li><a onclick="aa_tmpl_load('fb-demo.phtml');"><?php __p("FB");?></a></li>
                            <li><a onclick="aa_tmpl_load('module_registration.phtml');"><?php __p("Register");?></a></li>
                            <li><a onclick="aa_tmpl_load('form_validation.phtml');"><?php __p("Validation");?></a></li>
                            <li><a onclick="aa_tmpl_load('db-demo.phtml');"><?php __p("DB");?></a></li>
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
			<div class="terms-container">
				<?php
					$terms_and_conditions_link = "<a id='terms-link'>" . __t("terms_and_conditions") . "</a>";
        			__p("footer_terms", $terms_and_conditions_link);
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

        <!-- Show admin panel if user is admin -->
        <?php
        //if (is_fb_user_admin()) {
        //include_once 'modules/admin_panel/admin_panel.php';?>
        <?php //} ?>

        <?php
        /* Initialize App-Arena variable in js */
        $aaForJs = array(
            "t" => $aa['locale'][$aa_locale_current],
            "conf" => $aa['config'],
            "inst" => $aa['instance'],
            "fb" => false
        );
        if (isset($aa['fb'])) { $aaForJs["fb"] = $aa['fb']; }
        // Remove sensitive data from js object
        if (isset($aaForJs['inst']['fb_app_secret'])) { unset($aaForJs['inst']['fb_app_secret']); }
        if (isset($aaForJs['inst']['aa_app_secret'])) { unset($aaForJs['inst']['aa_app_secret']); }
        ?>

        <script>
            aa = aa || {};
            aa = <?php echo json_encode($aaForJs); ?>;
        </script>

		<!-- Debug mode -->
		<?php if ( isset( $aa['config']['admin_debug_mode']['value'] ) && $aa['config']['admin_debug_mode']['value'] ) {?>
			<span class="btn" onclick='jQuery("#_debug").toggle();'>Show debug info</span>
			<div id="_debug" style="display:none;">
				<h2>Debug information</h2>
				<h3>$aa['fb']</h3>
				<pre><?php var_dump($aa['fb']);?></pre>
				<h3>$aa['instance']</h3>
				<pre><?php var_dump($aa['instance']);?></pre>
				<h3>$aa['locale']</h3>
				<pre><?php var_dump($aa['locale']);?></pre>
				<h3>$aa['config']</h3>
				<pre><?php var_dump($aa['config']);?></pre>
				<h3>$_COOKIE</h3>
				<pre><?php var_dump($_COOKIE);?></pre>
			</div>
		<?php } ?>

        <!-- Show loading screen -->
		<?php require_once( dirname(__FILE__).'/templates/loading_screen.phtml' ); ?>
		
		<!-- google analytics stuff -->
		<script>
			var _gaq = _gaq || [];
			var ga_id = '<?php if ( isset( $aa['config']["ga_id"]["value"] ) ) echo $aa['config']["ga_id"]["value"];?>';	
			_gaq.push(['_setAccount', ga_id]);
			_gaq.push(['_gat._anonymizeIp']);
			_gaq.push(['_trackPageview']);
			_gaq.push(['_setCustomVar', 1, 'aa_inst_id', '<?php if ( isset( $aa['instance']["aa_inst_id"] ) ) echo $aa['instance']["aa_inst_id"];?>']);
			(function(d,t){var g=d.createElement(t),s=d.getElementsByTagName(t)[0];g.async=1;
			g.src=('https:'==location.protocol?'//ssl':'//www')+'.google-analytics.com/ga.js';
			s.parentNode.insertBefore(g,s)}(document,'script'));
		</script>
        <!-- data-main attribute tells require.js to load scripts/main.js after require.js loads. -->
        <script data-main="js/main" src="js/require.js"></script>

	</body>
</html>
