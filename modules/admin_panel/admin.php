<?php
require_once(dirname(__FILE__) . '/../../init.php');

// Check if user is Admin
if (is_fb_user_admin()) {
    ?>

<div id="admin_panel">
    <div class="panel-content">
        <div class="export-participants">
            <span class="btn btn-large"
                  onclick="return popup('admin/index.php?p=exportparticipants&aa_inst_id=<?php echo $session->instance['aa_inst_id']; ?>');"><i
                    class="icon-download-alt"></i> <?php __p("Export participants")?></span>
        </div>
        <div class="select-winner">
            <span class="btn btn-large"
                  onclick="return popup('admin/index.php?p=getwinner&aa_inst_id=<?php echo $session->instance['aa_inst_id']; ?>');"><i
                    class="icon-gift"></i> <?php __p("Select winner")?></span>
        </div>
    </div>

    <div class="configure-app">
        <a class="app_config" href="/manager/index.php/wizard/instance_id/<?=$aa['instance']['aa_inst_id'];?>/"
           target="_blank">
            <button class="btn btn-mini"><i class="icon-cog"></i> <?php __p("Configure_App")?></button>
        </a>
    </div>
</div>

<script type="text/javascript">
    function popup(url) {
        fenster = window.open(url, "Admin panel", "width=900,height=650,resizable=no");
        fenster.focus();
        return false;
    }

</script>

<?php
}
?>
