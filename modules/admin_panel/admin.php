<?php
require_once(dirname(__FILE__) . '/../../init.php');

// Check if user is Admin
if (is_fb_user_admin()) {
    ?>

<div id="admin_panel">
    <div class="panel-content">
        <span class="export-participants">
            <span class="btn btn-mini"
                  onclick="return popup('modules/admin_panel/index.php?p=exportparticipants&aa_inst_id=<?php echo $aa['instance']['aa_inst_id']; ?>');"><i
                    class="icon-download-alt"></i> <?php __p("Export participants")?></span>
        </span>
        <span class="select-winner">
            <span class="btn btn-mini"
                  onclick="return popup('modules/admin_panel/index.php?p=getwinner&aa_inst_id=<?php echo $aa['instance']['aa_inst_id']; ?>');"><i
                    class="icon-gift"></i> <?php __p("Select winner")?></span>
        </span>
        <span class="configure-app">
            <a class="app_config" href="/manager/index.php/wizard/instance_id/<?=$aa['instance']['aa_inst_id'];?>/"
               target="_blank">
                <button class="btn btn-mini"><i class="icon-cog"></i> <?php __p("Configure_App")?></button>
            </a>
        </span>
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
