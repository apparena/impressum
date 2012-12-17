<!-- Admin Panel-->
<?
require_once(dirname(__FILE__) . '/../../init.php');
$is_fb_user_admin = is_fb_user_admin();
if (is_fb_user_admin()) {
    ?>
<script>
    function admin_panel_open() {
        $('.admin_div').css('z-index', '1050');
        $('.adminpanel').slideDown('slow');
        $('#admin_panel_button').attr('onclick', 'admin_panel_close()');
        $('#arrow').removeClass('icon-chevron-down').addClass('icon-chevron-up');
    }
    function admin_panel_close() {
        $('.adminpanel').slideUp('slow', function () {
            $('.admin_div').css('z-index', '1043');
        });
        $('#admin_panel_button').attr('onclick', 'admin_panel_open()');
        $('#arrow').removeClass('icon-chevron-up').addClass('icon-chevron-down');
    }
</script>



<div class="adminpanel hide">
    <div id="adminpanel_header"><h3><? __p('admin_panel') ?></h3></div>
    <? require_once dirname(__FILE__) . '/admin.php'; ?>
</div>
<span class="admin_banner">
<div id="admin_panel_button" class="clickable" onclick="admin_panel_open()"><i
        class="icon-cog"></i>&nbsp;<? __p('admin_panel')?>&nbsp;<i id="arrow" class="icon-chevron-up"></i></div>
</span>
<div style="clear:both"></div>

<script>
    var fb_is_admin = '<? echo $is_fb_user_admin;?>';
    //TODO:check for super admins
    // Show admin panel if user is admin
    if (fb_is_admin) {
        $('.admin_div').css('z-index', '1050');
        $('.adminpanel').slideDown('slow').delay(2000).slideUp('slow', function () {
            $('#admin_panel_button').attr('onclick', 'admin_panel_open()');
            $('#arrow').removeClass('icon-chevron-up').addClass('icon-chevron-down');
            $('.admin_div').css('z-index', '1043');
        });
    }
</script>

<? } ?>