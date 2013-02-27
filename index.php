<?php
include_once("init.php");
?>
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

    <!-- Include css config values here -->
    <style type="text/css">
        <?php
        echo $aa['config']['css_bootstrap']['value'];
        echo $aa['config']['css_app']['value'];
        echo $aa['config']['css_user']['value'];
        ?>
    </style>

    <?php if ($aa['config']['footer_branding']['value'] == 'banner') { ?>
    <!-- Google Publisher -->
    <script type='text/javascript'>
        var googletag = googletag || {};
        googletag.cmd = googletag.cmd || [];
        (function () {
            var gads = document.createElement('script');
            gads.async = true;
            gads.type = 'text/javascript';
            var useSSL = 'https:' == document.location.protocol;
            gads.src = (useSSL ? 'https:' : 'http:') +
                    '//www.googletagservices.com/tag/js/gpt.js';
            var node = document.getElementsByTagName('script')[0];
            node.parentNode.insertBefore(gads, node);
        })();
    </script>
    <script type='text/javascript'>
        googletag.cmd.push(function () {
            googletag.defineSlot('/114327208/<?=$aa['config']['footer_branding_dfp_inv_name']['value']?>', [810, 90], '<?=$aa['config']['footer_branding_dfp_div_id']['value']?>').addService(googletag.pubads());
            googletag.pubads().enableSingleRequest();
            googletag.enableServices();
        });
    </script>
    <?php } ?>
</head>

<body>
<!-- Here starts the header -->
<!-- Prompt IE 6 users to install Chrome Frame. Remove this if you support IE 6.
     chromium.org/developers/how-tos/chrome-frame-getting-started -->
<!--[if lt IE 7]><p class=chromeframe>Your browser is <em>ancient!</em> <a href="http://browsehappy.com/">Upgrade to a
    different browser</a> or <a href="http://www.google.com/chromeframe/?redirect=true">install Google Chrome Frame</a>
    to experience this site.</p><![endif]-->

<!-- Show admin panel and admin intro information -->
<?  if ( $aa['fb']['page']['admin'] ) {  ?>
    <div class="admin_div">
        <? require_once dirname(__FILE__) . '/modules/admin_panel/admin_panel.php'; ?>
    </div>
    <div class="modal hide fade" id="admin_modal">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
            <? __p('admin_intro_header')?>
        </div>
        <div class="modal-body">
            <?php echo $aa['config']["admin_intro"]['value']?>
        </div>
        <div class="modal-footer">
            <label class="checkbox"><input type="checkbox" id="admin-intro"><? __p('do_not_show_any_more') ?></label>
            <a href="#" class="btn" data-dismiss="modal" onclick="setAdminIntroCookie();">
                <i class="icon-remove"></i> <? __p('close') ?>
            </a>
        </div>
    </div>
<?php }?>

<!-- Facebook Fangate -->
<?php if ($aa['fb']['is_fb_user_fan'] == false && $aa['config']['fangate_activated']['value']) {  ?>
    <div id="fangate" class="fangate">
        <div class="img_non_fans">
            <?php if ( $aa['config']['fangate_closable']['value'] ) { ?>
            <a class="btn pull-right" onclick="$('#fangate').hide();">&times;</a>
            <?php } ?>
            <div class="like-button">
                <div class="fb-like" data-href="<?=$aa['instance']['fb_page_url']?>" data-send="false"
                     data-layout="box_count" data-width="200"
                     data-show-faces="false" data-colorscheme="light" data-action="like">
                </div>
            </div>

            <img src="<?php echo $aa['config']['fangate']['value']?>"/>
        </div>
        <div class="backdrop">&nbsp;</div>
    </div>
<?php }?>


<!-- this is the div you can append info/alert/error messages to (will be showing between the menu and the content by default) -->
<div id="msg-container"></div>

<div class="custom-header">
    <?php  echo $aa['config']['header_custom']['value'];  ?>
</div>

<div id="main" class="container">
    <!-- the main content is managed by initApp() -->
</div>
<!-- #main -->

<div class="custom-footer">
    <?php  echo $aa['config']['footer_custom']['value'];  ?>
</div>

<footer>
    <?php if ( $aa['config']['footer_branding']['value'] == 'banner' || $aa['config']['footer_branding']['value'] == 'text' ) { ?>
    <div class="banner">
        <div class="tagline pull-left"><?php __p("powered_by"); ?></div>
        <div class="like-button pull-right">
            <div class="fb-like" data-href="<?=$aa['config']['footer_branding_fblike_url']['value']?>" data-send="false"
                 data-layout="button_count" data-width="200" data-show-faces="false"></div>
        </div>
        <?php if ( $aa['config']['footer_branding']['value'] == 'banner' ) { ?>
        <!-- 10000-Template-App-Footer -->
        <div id='<?=$aa['config']['footer_branding_dfp_div_id']['value']?>' style='width:810px; height:90px;'>
            <script type='text/javascript'>
                googletag.cmd.push(function () {
                    googletag.display('<?=$aa['config']['footer_branding_dfp_div_id']['value']?>');
                });
            </script>
        </div>
        <?php } ?>
    </div>
    <?php } ?>
</footer>

<!-- Modal container -->
<div id="modal" class="modal hide fade"></div>

<?php
/* Initialize App-Arena variable in js */
$aaForJs = array(
    "t" => $aa['locale'][$aa_locale_current],
    "conf" => $aa['config'],
    "inst" => $aa['instance'],
    "fb" => false
);
if (isset($aa['fb'])) {
    $aaForJs["fb"] = $aa['fb'];
}
// Remove sensitive data from js object
if (isset($aaForJs['inst']['fb_app_secret'])) {
    unset($aaForJs['inst']['fb_app_secret']);
}
if (isset($aaForJs['inst']['aa_app_secret'])) {
    unset($aaForJs['inst']['aa_app_secret']);
}
?>

<script>
    aa = <?php echo json_encode($aaForJs); ?>;
</script>

<!-- Debug mode -->
<?php if (isset($aa['config']['admin_debug_mode']['value']) && $aa['config']['admin_debug_mode']['value']) { ?>
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
<?php require_once(dirname(__FILE__) . '/templates/loading_screen.phtml'); ?>

<?php if ( $aa['config']['ga_activated']['value'] ) { ?>
<!-- google analytics Integration -->
<script>
    var _gaq = _gaq || [];
    var ga_id = '<?php if ( isset( $aa['config']["ga_id"]["value"] ) ) echo $aa['config']["ga_id"]["value"]; ?>';
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
<?php } ?>

<script src="js/components/jquery/jquery-1.7.1.min.js"></script>
<!-- data-main attribute tells require.js to load scripts/main.js after require.js loads. -->
<script data-main="js/main" src="js/require.js"></script>

</body>
</html>
