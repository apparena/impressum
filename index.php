<?php
include_once("init.php");
?>
<!-- HTML5 standard doctype -->
<!doctype html>
<!--[if lt IE 7]> <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang="en"> <![endif]-->
<!--[if IE 7]>    <html class="no-js lt-ie9 lt-ie8" lang="en"> <![endif]-->
<!--[if IE 8]>    <html class="no-js lt-ie9" lang="en"> <![endif]-->
<!--[if gt IE 8]><!-->
<html class="no-js" lang="en"> <!--<![endif]-->
<html>
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <meta charset="utf-8">

    <!-- Facebook Meta Data -->
    <meta property="fb:app_id" content="<?php echo $aa['instance']['fb_app_id']?>"/>
    <meta property="og:title" content=""/>
    <meta property="og:type" content="website"/>
    <meta property="og:url"
          content="<?php echo $aa['instance']['fb_page_url'] . "?sk=app_" . $aa['instance']['fb_app_id']?>"/>
    <meta property="og:image" content=""/>
    <meta property="og:site_name" content=""/>
    <meta property="og:description" content=""/>

    <!-- We have no old school title in a facebook app -->
    <title></title>
    <meta name="description" content="">
    <meta name="author" content="iConsultants UG - www.app-arena.com">

    <meta name="viewport" content="width=device-width">

    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
    <script>window.jQuery || document.write('<script src="js/libs/jquery-1.7.1.min.js"><\/script>')</script>

    <!-- scripts concatenated and minified via ant build script-->
    <!-- <script src="js/libs/modernizr-2.5.2-respond-1.1.0.min.js"></script> -->
    <script src="js/bootstrap.min.js"></script>
    <script src="js/plugins.js"></script>
    <script src="js/script.js?v4"></script>
    <!-- end scripts-->

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
<!--[if lt IE 7]><p class=chromeframe>Your browser is <em>ancient!</em> <a href="http://browsehappy.com/">Upgrade to a
    different browser</a> or <a href="http://www.google.com/chromeframe/?redirect=true">install Google Chrome Frame</a>
    to experience this site.</p><![endif]-->

<?
if (is_fb_user_admin()) {
    ?>
<div class="admin_div">
    <? require_once dirname(__FILE__) . '/modules/admin_panel/admin_panel.php'; ?>
</div>
    <?
}


if ($aa['fb']['is_fb_user_fan'] == false && $aa['config']['nofan_image_activated']['value']) {
    ?>
<div class="page_non_fans_layer">
    <div class="img_non_fans">
        <img src="<?php echo $aa['config']['nofan_image']['value']?>"/>
    </div>
    <div id="non_fan_background">&nbsp;</div>
</div>
    <?php }?>


<div class="navbar">
    <div class="navbar-inner">
        <div class="container-fluid">
            <nav>
                <ul class="nav">
                    <li><a onclick="aa_tmpl_load('index.phtml');"><?php __p("Homepage");?></a></li>
                    <li><a onclick="aa_tmpl_load('localization.phtml');"><?php __p("localization");?></a></li>
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
</div>
<!-- #main -->

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
        if (isset($aa['config']['footer_activated']['value']) && $aa['config']['footer_activated']['value'] == '1') {
            echo $aa['config']['footer']['value'];
        }
        ?>
    </div>
</footer>

<!-- Debug mode -->
<?php if (isset($aa['config']['admin_debug_mode']['value']) && $aa['config']['admin_debug_mode']['value']) { ?>
<span class="btn" onclick='jQuery("#_debug").toggle();'>Show debug info</span>
<div id="_debug" style="display:none;">
    <h1>Debug information</h1>

    <h3>$aa['fb']</h3>
				<pre>
				<?php var_dump($aa['fb']);?>
				</pre>
    <h3>$aa['instance']</h3>
				<pre>
				<?php var_dump($aa['instance']);?>
				</pre>
    <h3>$aa['locale']</h3>
				<pre>
				<?php var_dump($aa['locale']);?>
				</pre>
    <h3>$aa['config']</h3>
				<pre>
				<?php var_dump($aa['config']);?>
				</pre>
    <h3>$_COOKIE</h3>
				<pre>
				<?php var_dump($_COOKIE);?>
				</pre>
</div>
    <?php } ?>

<?php
// Include the file for the loading screen. Use it later from Javascript to hide_loading() or show_loading().
require_once(dirname(__FILE__) . '/templates/loading_screen.phtml');
?>



<!-- google analytics stuff -->
<script>
    var _gaq = _gaq || [];
    var ga_id = '<?php if (isset($aa['config']["ga_id"]["value"])) echo $aa['config']["ga_id"]["value"];?>';
    _gaq.push(['_setAccount', ga_id]);
    _gaq.push(['_gat._anonymizeIp']);
    _gaq.push(['_trackPageview']);
    _gaq.push(['_setCustomVar', 1, 'aa_inst_id', '<?php if (isset($aa['instance']["aa_inst_id"])) echo $aa['instance']["aa_inst_id"];?>']);
    (function (d, t) {
        var g = d.createElement(t), s = d.getElementsByTagName(t)[0];
        g.async = 1;
        g.src = ('https:' == location.protocol ? '//ssl' : '//www') + '.google-analytics.com/ga.js';
        s.parentNode.insertBefore(g, s)
    }(document, 'script'));
</script>

<!--[if lt IE 7 ]>
<script src="//ajax.googleapis.com/ajax/libs/chrome-frame/1.0.2/CFInstall.min.js"></script>
<script>window.attachEvent("onload", function () {
    CFInstall.check({mode:"overlay"})
})</script>
<![endif]-->

<div id="fb-root"></div>
<script type="text/javascript">

    fb_app_id = '<?php echo $aa['instance']["fb_app_id"]?>';
    fb_canvas_url = '<?php echo $aa['instance']["fb_canvas_url"]?>';
    aa_inst_id = '<?php echo $aa['instance']["aa_inst_id"]?>';

    // jquerys document ready function gets fired when the DOM has been finished loading.
    $(document).ready(function () {
        show_loading(); // uses the formerly included "loading_screen.phtml" files function
        aa_tmpl_load("index.phtml");

        $('#terms-link').click(function () {
            aa_tmpl_load('terms.phtml');
        });
    });


    window.fbAsyncInit = function () {
        FB.init({
            appId:fb_app_id, // App ID
            channelUrl:fb_canvas_url + 'channel.html', // Channel File
            status:true, // check login status
            cookie:true, // enable cookies to allow the server to access the session
            xfbml:true  // parse XFBML
        });
        FB.Canvas.setAutoGrow(); // Grow facebook page, when iframe content is growing (>> no scrollbar)
        // Additional initialization code here
    };

    // Load the SDK Asynchronously
    (function (d) {
        var js, id = 'facebook-jssdk', ref = d.getElementsByTagName('script')[0];
        if (d.getElementById(id)) {
            return;
        }
        js = d.createElement('script');
        js.id = id;
        js.async = true;
        js.src = "//connect.facebook.net/de_DE/all.js";
        ref.parentNode.insertBefore(js, ref);
    }(document));
</script>

<!-- Show admin panel if user is admin -->
<?php
//if (is_fb_user_admin()) {
//include_once 'modules/admin_panel/admin_panel.php';?>
<?php //} ?>

</body>
</html>
